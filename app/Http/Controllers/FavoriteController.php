<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function getAllFavorites(Request $request)
    {
        $user = $request->user();
        $favorites = $user->favorites;

        return response()->json([
            'favorites' => $favorites,
        ], 200);
    }

    public function toggleFavorite(Request $request, $id)
    {
        $user = $request->user();
        $announcement = Announcement::findOrFail($id);

        $favorite = $announcement->favorites()->where('user_id', $user->id)->first();

        if (!$favorite) {
            Favorite::create([
                'announcement_id' => $announcement->id,
                'user_id' => $user->id,
            ]);
            $message = 'Favorite added';
        } else {
            $favorite->delete();
            $message = 'Favorite removed';
        }

        return response()->json([
            'message' => $message,
        ], 200);
    }
}
