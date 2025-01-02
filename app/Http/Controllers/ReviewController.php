<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ReviewCreateRequest;
use App\Http\Requests\Auth\ReviewUpdateRequest;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function createReview(ReviewCreateRequest $request, $id)
    {
        $ratedUser = User::findOrFail($id);
        if ($ratedUser->id === $request->user()->id) {
            return response()->json([
                'message' => 'You cannot comment to yourself',
            ], 403);
        }

        $validated = $request->validated();
        $review = new Review($validated);
        $review->valuator_user_id = $request->user()->id;
        $review->rated_user_id = $ratedUser->id;
        $review->save();

        return response()->json([
            'message' => 'Review created successfully',
            'review' => $review,
        ], 201);
    }

    public function updateReview(ReviewUpdateRequest $request, $id)
    {
        $review = Review::findOrFail($id);
        if ($review->valuator_user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You cannot update a review that is not yours',
            ], 403);
        }

        $validated = $request->validated();
        $review->fill($validated);
        $review->save();

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review,
        ], 200);
    }

    public function deleteReview(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $userId = $request->user()->id;

        if ($review->rated_user_id == $userId || $review->valuator_user_id == $userId) {
            $review->delete();
            return response()->json([
                'message' => 'Review deleted successfully',
            ], 200);
        }

        return response()->json([
            'message' => 'You cannot delete this review',
        ], 403);
    }
}
