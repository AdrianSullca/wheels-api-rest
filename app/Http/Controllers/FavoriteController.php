<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class FavoriteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/favorites",
     *     tags={"Favorites"},
     *     summary="Get all favorite announcements of the authenticated user",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of favorites",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="favorites",
     *                 type="array",
     *                 @OA\Items(type="object", example={"id": 1, "announcement_id": 42, "created_at": "2025-01-01T12:00:00Z"})
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required"
     *     )
     * )
     */
    public function getAllFavorites(Request $request)
    {
        $user = $this->authenticateUser($request);
        $favorites = $user->favorites;

        return response()->json([
            'favorites' => $favorites,
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/favorites/{announcement_id}/toggle",
     *     tags={"Favorites"},
     *     summary="Toggle favorite status for an announcement",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="announcement_id",
     *         in="path",
     *         required=true,
     *         description="ID of the announcement",
     *         @OA\Schema(type="integer", example=42)
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Favorite added",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Favorite added"),
     *             @OA\Property(property="favorite", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Favorite removed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Favorite removed"),
     *             @OA\Property(property="favorite", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Announcement not found"
     *     )
     * )
     */
    public function toggleFavorite(Request $request, $announcement_id)
    {
        $user = $this->authenticateUser($request);
        $announcement = Announcement::findOrFail($announcement_id);

        $favorite = $announcement->favorites()->where('user_id', $user->id)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Favorite removed', 'favorite' => false], 200);
        } else {
            $announcement->favorites()->create(['user_id' => $user->id]);
            return response()->json(['message' => 'Favorite added', 'favorite' => true], 201);
        }
    }
}
