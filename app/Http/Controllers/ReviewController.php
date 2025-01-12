<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ReviewCreateRequest;
use App\Http\Requests\Auth\ReviewUpdateRequest;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/reviews/{id}/create",
     *     tags={"Reviews"},
     *     summary="Create a review for a user",
     *     description="Allows an authenticated user to create a review for another user. The reviewer cannot comment on themselves.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user being reviewed",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="rating", type="integer", example=5),
     *             @OA\Property(property="comment", type="string", example="Great experience!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Review created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Review created successfully"),
     *             @OA\Property(property="review", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="rating", type="integer", example=5),
     *                 @OA\Property(property="comment", type="string", example="Great experience!")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="You cannot comment to yourself"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="comment", type="array", @OA\Items(type="string", example="The comment field is required.")),
     *                 @OA\Property(property="rating", type="array", @OA\Items(type="string", example="The rating field is required."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required"
     *     )
     * )
     */
    public function createReview(ReviewCreateRequest $request, $id)
    {
        $userAuthenticated = $this->authenticateUser($request);
        $ratedUser = User::findOrFail($id);
        if ($ratedUser->id === $userAuthenticated->id) {
            return response()->json([
                'message' => 'You cannot comment to yourself',
            ], 403);
        }

        $validated = $request->validated();
        $review = new Review($validated);
        $review->valuator_user_id = $userAuthenticated->id;
        $review->rated_user_id = $ratedUser->id;
        $review->save();

        return response()->json([
            'message' => 'Review created successfully',
            'review' => $review,
        ], 201);
    }

    /**
     * @OA\Patch(
     *     path="/api/reviews/{id}",
     *     tags={"Reviews"},
     *     summary="Update an existing review",
     *     description="Allows an authenticated user to update their own review. The reviewer cannot update a review they did not create.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the review to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="rating", type="integer", example=4),
     *             @OA\Property(property="comment", type="string", example="Updated comment on the review")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Review updated successfully"),
     *             @OA\Property(property="review", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="rating", type="integer", example=4),
     *                 @OA\Property(property="comment", type="string", example="Updated comment on the review")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="You cannot update a review that is not yours"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="comment", type="array", @OA\Items(type="string", example="The comment field must be a string.")),
     *                 @OA\Property(property="rating", type="array", @OA\Items(type="string", example="The rating field must be a number."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required"
     *     )
     * )
     */
    public function updateReview(ReviewUpdateRequest $request, $id)
    {
        $userAuthenticated = $this->authenticateUser($request);
        $review = Review::findOrFail($id);
        if ($review->valuator_user_id !== $userAuthenticated->id) {
            return response()->json([
                'message' => 'You cannot update a review that is not yours',
            ], 403);
        }

        $validated = $request->validated();
        $review->fill($validated);
        $review->update();

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review,
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/reviews/{id}",
     *     tags={"Reviews"},
     *     summary="Delete an existing review",
     *     description="Allows an authenticated user to delete a review. The user can delete a review they either created or are the subject of.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the review to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Review deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="You cannot delete this review",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="You cannot delete this review")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Review not found"
     *     )
     * )
     */
    public function deleteReview(Request $request, $id)
    {
        $userAuthenticated = $this->authenticateUser($request);
        $userId = $userAuthenticated->id;
        $review = Review::findOrFail($id);

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
