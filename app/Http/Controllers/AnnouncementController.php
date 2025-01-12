<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AnnouncementCreateRequest;
use App\Http\Requests\Auth\AnnouncementUpdateRequest;
use App\Models\Announcement;
use App\Models\Photo;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

/**
 * @OA\Info(
 *     title="Wheels API",
 *     version="1.0.0",
 *     description="API for wheels app",
 *     @OA\Contact(
 *         email="adrian.sullca@cirvianum.cat"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost",
 *     description="Local development server"
 * )
 */
class AnnouncementController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/announcements",
     *     tags={"Announcements"},
     *     summary="Retrieve all active announcements except those created by the authenticated user",
     *     @OA\Response(
     *         response=200,
     *         description="List of announcements retrieved successfully",
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function getAllAnnouncements(Request $request)
    {
        $user = $this->authenticateUser($request);

        $announcements = Announcement::where('state', 'active')
            ->where('user_id', '!=', $user->id)
            ->with('user')
            ->with('photos')
            ->get();

        $announcements = $announcements->map(function ($announcement) {
            $announcement->photoUrls = $announcement->photos->pluck('image_url');
            unset($announcement->photos);
            return $announcement;
        });

        return response()->json([
            'message' => 'Announcements retrieved successfully',
            'announcements' => $announcements,
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/announcements/create",
     *     tags={"Announcements"},
     *     summary="Create a new announcement",
     *     description="Creates a new announcement with details such as title, description, model, brand, year, and associated photos.",
     *     @OA\Response(
     *         response=201,
     *         description="Announcement created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Announcement created successfully"),
     *             @OA\Property(property="announcement", type="object", 
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Brand new sedan"),
     *                 @OA\Property(property="description", type="string", example="Low mileage, well-maintained car."),
     *                 @OA\Property(property="model", type="string", example="Corolla"),
     *                 @OA\Property(property="brand", type="string", example="Toyota"),
     *                 @OA\Property(property="kilometers", type="number", example=15000),
     *                 @OA\Property(property="year", type="integer", example=2022),
     *                 @OA\Property(property="price", type="number", example=25000),
     *                 @OA\Property(property="state", type="string", example="active"),
     *                 @OA\Property(property="vehicleType", type="string", example="sedan"),
     *                 @OA\Property(property="photoUrls", type="array",
     *                     @OA\Items(type="string", example="https://res.cloudinary.com/example/image/upload/photo1.jpg")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Authentication required. Please log in.")
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Announcement details",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Brand new sedan"),
     *             @OA\Property(property="description", type="string", example="Low mileage, well-maintained car."),
     *             @OA\Property(property="model", type="string", example="Corolla"),
     *             @OA\Property(property="brand", type="string", example="Toyota"),
     *             @OA\Property(property="kilometers", type="number", example=15000),
     *             @OA\Property(property="year", type="integer", example=2022),
     *             @OA\Property(property="price", type="number", example=25000),
     *             @OA\Property(property="state", type="string", example="active"),
     *             @OA\Property(property="photos", type="array",
     *                 @OA\Items(type="string", format="binary")
     *             ),
     *             @OA\Property(property="vehicleType", type="string", example="sedan")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function createAnnouncement(AnnouncementCreateRequest $request)
    {
        $user = $this->authenticateUser($request);

        $validatedData = $request->validated();
        $announcement = new Announcement($validatedData);
        $announcement->user_id = $user->id;
        $announcement->state = 'active';
        $announcement->save();

        $photos = $request->file('photos');
        foreach ($photos as $photo) {
            $cloudinaryImage = $photo->storeOnCloudinary('announcements_photos');
            $url = $cloudinaryImage->getSecurePath();
            $public_id = $cloudinaryImage->getPublicId();
            Photo::create([
                'announcement_id' => $announcement->id,
                'image_url' => $url,
                'image_public_id' => $public_id,
            ]);
        }

        return response()->json([
            'message' => 'Announcement created successfully',
            'announcement' => $announcement->load('photos'),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/announcements/{id}",
     *     tags={"Announcements"},
     *     summary="Get a specific announcement by ID",
     *     description="Fetches the details of an announcement, including its associated photos and user information. The announcement must be active unless the requester is the owner.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the announcement to fetch",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Announcement details fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="announcement", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Brand new sedan"),
     *                 @OA\Property(property="description", type="string", example="Low mileage, well-maintained car."),
     *                 @OA\Property(property="model", type="string", example="Corolla"),
     *                 @OA\Property(property="brand", type="string", example="Toyota"),
     *                 @OA\Property(property="kilometers", type="integer", example=15000),
     *                 @OA\Property(property="year", type="integer", example=2022),
     *                 @OA\Property(property="price", type="number", example=25000),
     *                 @OA\Property(property="state", type="string", example="active"),
     *                 @OA\Property(property="photoUrls", type="array",
     *                     @OA\Items(type="string", example="https://example.com/photo.jpg")
     *                 ),
     *                 @OA\Property(property="isFavorite", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Announcement not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Announcement not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Authentication required. Please log in.")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function getAnnouncement(Request $request, $id)
    {
        $user = $this->authenticateUser($request);

        $announcement = Announcement::with(['photos', 'user'])
            ->where('id', $id)
            ->first();

        if (!$announcement) {
            return response()->json([
                'message' => 'Announcement not found',
            ], 404);
        }

        if ($announcement->user_id !== $user->id && $announcement->state !== 'active') {
            return response()->json([
                'message' => 'Announcement not found',
            ], 404);
        }

        $announcement->photoUrls = $announcement->photos->pluck('image_url');
        unset($announcement->photos);

        $isFavorite = $user->favorites()->where('announcement_id', $announcement->id)->exists();
        $announcement->isFavorite = $isFavorite;

        return response()->json([
            'announcement' => $announcement,
        ], 200);
    }

    /**
     * @OA\Patch(
     *     path="/api/announcements/{id}",
     *     tags={"Announcements"},
     *     summary="Update an existing announcement",
     *     description="Allows a user to update their announcement's details, manage photos (add or remove), and modify its state.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the announcement to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated title"),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="vehicleType", type="string", example="sedan"),
     *             @OA\Property(property="brand", type="string", example="Toyota"),
     *             @OA\Property(property="year", type="integer", example=2022),
     *             @OA\Property(property="model", type="string", example="Corolla"),
     *             @OA\Property(property="kilometers", type="integer", example=15000),
     *             @OA\Property(property="price", type="number", example=25000),
     *             @OA\Property(property="state", type="string", example="active"),
     *             @OA\Property(property="oldPhotos", type="array", @OA\Items(type="string", example="https://example.com/photo1.jpg")),
     *             @OA\Property(property="newPhotos", type="array", @OA\Items(type="string", format="binary"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Announcement updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Announcement updated successfully"),
     *             @OA\Property(property="announcement", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Updated title"),
     *                 @OA\Property(property="description", type="string", example="Updated description"),
     *                 @OA\Property(property="state", type="string", example="active"),
     *                 @OA\Property(property="photos", type="array", @OA\Items(type="string", example="https://example.com/photo.jpg"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized action",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You are not authorized to update this announcement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Announcement not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Announcement not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Authentication required. Please log in.")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(AnnouncementUpdateRequest $request, $id)
    {
        $user = $this->authenticateUser($request);

        $announcement = Announcement::find($id);
        if (!$announcement) {
            return response()->json(['message' => 'Announcement not found'], 404);
        }

        if ($announcement->user_id !== $user->id) {
            return response()->json(['message' => 'You are not authorized to update this announcement'], 403);
        }

        $oldPhotos = $announcement->photos->pluck('image_url')->toArray();
        $oldPhotosJson = $request->input('oldPhotos');

        $oldPhotosRequest = json_decode($oldPhotosJson, true);

        $photosToDelete = array_diff($oldPhotos, $oldPhotosRequest);

        if ($photosToDelete) {
            foreach ($photosToDelete as $photoUrl) {
                $photoToDelete = $announcement->photos()->where('image_url', $photoUrl)->first();

                if ($photoToDelete) {
                    Cloudinary::destroy($photoToDelete->image_public_id);
                    $photoToDelete->delete();
                }
            }
        }

        if ($request->has('newPhotos')) {
            $newPhotos = $request->file('newPhotos');

            if (is_array($newPhotos)) {
                foreach ($newPhotos as $photo) {
                    if ($photo && $photo->isValid()) {
                        $cloudinaryImage = $photo->storeOnCloudinary('announcements_photos');
                        $url = $cloudinaryImage->getSecurePath();
                        $public_id = $cloudinaryImage->getPublicId();

                        Photo::create([
                            'announcement_id' => $announcement->id,
                            'image_url' => $url,
                            'image_public_id' => $public_id,
                        ]);
                    }
                }
            }
        }

        $announcement->update($request->validated());

        return response()->json([
            'message' => 'Announcement updated successfully',
            'announcement' => $announcement,
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/announcements/{id}",
     *     tags={"Announcements"},
     *     summary="Delete an existing announcement",
     *     description="Allows a user to delete their own announcement, including its associated photos.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the announcement to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Announcement deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Announcement deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized action",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You are not authorized to delete this announcement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Announcement not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Announcement not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Authentication required. Please log in.")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function deleteAnnouncement(Request $request, $id)
    {
        $user = $this->authenticateUser($request);

        $announcement = Announcement::with('photos')->find($id);
        if (!$announcement) {
            return response()->json(['message' => 'Announcement not found'], 404);
        }

        if ($announcement->user_id !== $user->id) {
            return response()->json(['message' => 'You are not authorized to delete this announcement'], 403);
        }

        foreach ($announcement->photos as $photo) {
            Cloudinary::destroy($photo->image_public_id);
            $photo->delete();
        }

        $announcement->delete();

        return response()->json(['message' => 'Announcement deleted successfully'], 200);
    }
}
