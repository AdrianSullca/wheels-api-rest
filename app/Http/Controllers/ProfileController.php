<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/profile/{id}/stats",
     *     tags={"Profile"},
     *     summary="Get profile statistics for a user",
     *     description="Returns the statistics of a user's profile, including announcement count, review count, and total favorites.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user whose profile statistics are being fetched",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User profile statistics fetched successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", type="object", example={"id": 1, "name": "John Doe", "email": "john@example.com"}),
     *             @OA\Property(
     *                 property="stats",
     *                 type="object",
     *                 @OA\Property(property="announcements_count", type="integer", example=5),
     *                 @OA\Property(property="reviews_count", type="integer", example=10),
     *                 @OA\Property(property="total_favorites", type="integer", example=15)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required"
     *     )
     * )
     */
    public function getProfileStats(Request $request, $id)
    {
        $userAuthenticated = $this->authenticateUser($request);
        $userProfile = User::findOrFail($id);

        $announcementsQuery = $userProfile->announcements();

        if ($userAuthenticated->id != $id) {
            $announcementsQuery->where('state', 'active');
        }

        $announcementsCount = $announcementsQuery->count();
        $reviewsCount = Review::where('rated_user_id', $id)->count();

        $totalFavorites = $announcementsQuery->withCount('favorites')
            ->get()
            ->sum('favorites_count');

        return response()->json([
            'user' => $userProfile,
            'stats' => [
                'announcements_count' => $announcementsCount,
                'reviews_count' => $reviewsCount,
                'total_favorites' => $totalFavorites,
            ],
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/profile/{id}/announcements",
     *     tags={"Profile"},
     *     summary="Get all announcements by a user",
     *     description="Fetches a list of announcements made by a user. If the authenticated user is not the profile owner, only active announcements are returned.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user whose announcements are being fetched",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User's announcements fetched successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="userAuthenticated", type="object", 
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe")
     *             ),
     *             @OA\Property(
     *                 property="announcements",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Car for Sale"),
     *                     @OA\Property(
     *                         property="photoUrls",
     *                         type="array",
     *                         @OA\Items(type="string", example="https://example.com/photo1.jpg")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required"
     *     )
     * )
     */
    public function getUserAnnouncements(Request $request, $id)
    {
        $userAuthenticated = $request->user();
        $userProfile = User::findOrFail($id);
        $announcementsQuery = $userProfile->announcements()->with('photos');


        if ($userAuthenticated->id != $id) {
            $announcementsQuery->where('state', 'active');
        }

        $announcements = $announcementsQuery->get();

        $announcements = $announcements->map(function ($announcement) {
            $announcement->photoUrls = $announcement->photos->pluck('image_url');
            unset($announcement->photos);
            return $announcement;
        });

        return response()->json([
            'userAutenticated' => $userAuthenticated,
            'announcements' => $announcements,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/profile/{id}/reviews",
     *     tags={"Profile"},
     *     summary="Get reviews for a user",
     *     description="Fetches all reviews left for a user. Includes the details of the users who left the reviews.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user whose reviews are being fetched",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User reviews fetched successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="reviews",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     example={"id": 1, "rating": 5, "comment": "Great user!", "valuator": {"id": 2, "name": "Jane Doe"}}
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required"
     *     )
     * )
     */
    public function getUserReviews($id)
    {
        $userProfile = User::findOrFail($id);

        $reviews = Review::where('rated_user_id', $id)
            ->with('valuator')
            ->get()
            ->map(function ($review) {
                unset($review->valuator_user_id);
                return $review;
            });

        return response()->json([
            'user' => $userProfile,
            'reviews' => $reviews,
        ], 200);
    }
}
