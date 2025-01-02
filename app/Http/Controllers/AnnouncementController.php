<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AnnouncementCreateRequest;
use App\Http\Requests\Auth\AnnouncementUpdateRequest;
use App\Models\Announcement;
use App\Models\Photo;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AnnouncementController extends Controller
{
    public function getAllAnnouncements(Request $request)
    {
        $user = $request->user();
        $announcements = Announcement::where('state', 'active')
            ->where('user_id', '!=', $user->id)
            ->get();

        return response()->json([
            'announcements' => $announcements,
        ], 200);
    }

    public function createAnnouncement(AnnouncementCreateRequest $request)
    {
        $validatedData = $request->validated();

        $user = $request->user();
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

    public function getAnnouncement(Request $request, $id)
    {
        $user = $request->user();
        $announcement = Announcement::find($id);

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

        return response()->json([
            'announcement' => $announcement->load('photos'),
        ], 200);
    }

    // MODIFICAR PARA PODER AÑADIR O ELIMINAR FOTOS DE UN ANUNCIO
    public function updateAnnouncement(AnnouncementUpdateRequest $request, $id)
    {
        $announcement = Announcement::find($id);
        if (!$announcement) {
            return response()->json(['message' => 'Announcement not found'], 404);
        }

        $user = $request->user();
        if ($announcement->user_id !== $user->id) {
            return response()->json(['message' => 'You are not authorized to update this announcement'], 403);
        }

        $announcement->update($request->validated());

        return response()->json([
            'message' => 'Announcement updated successfully',
            'announcement' => $announcement,
        ], 200);
    }

    /* public function deleteAnnouncement(Request $request, $id)
    {
        $announcement = Announcement::with('photos')->find($id);
        if (!$announcement) {
            return response()->json(['message' => 'Announcement not found'], 404);
        }

        $user = $request->user();
        if ($announcement->user_id !== $user->id) {
            return response()->json(['message' => 'You are not authorized to delete this announcement'], 403);
        }

        foreach ($announcement->photos as $photo) {
            Cloudinary::destroy($photo->image_public_id);
            $photo->delete();
        }

        $announcement->delete();

        return response()->json(['message' => 'Announcement deleted successfully'], 200);
    } */
}
