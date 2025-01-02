<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function getProfileStats(Request $request, $id)
    {
        $userAuthenticated = $request->user();
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

    public function getUserAnnouncements(Request $request, $id)
    {
        $userAuthenticated = $request->user();
        $userProfile = User::findOrFail($id);
        $announcementsQuery = $userProfile->announcements();

        if ($userAuthenticated->id != $id) {
            $announcementsQuery->where('state', 'active');
        }

        $announcements = $announcementsQuery->get();

        return response()->json([
            'announcements' => $announcements,
        ], 200);
    }

    public function updateProfile(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $validatedData = $request->validated();

        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ], 200);
    }

    public function getUserReviews($id)
    {
        $userProfile = User::findOrFail($id);
        $reviews = Review::where('rated_user_id', $id)->get();

        return response()->json([
            'reviews' => $reviews,
        ], 200);
    }
}
