<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AdminController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/users",
     *     tags={"Admin"},
     *     summary="Retrieve all users except the authenticated admin",
     *     description="Fetches a list of all users in the system, excluding the authenticated admin.",
     *     @OA\Response(
     *         response=200,
     *         description="List of users retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="users", type="array", 
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                     @OA\Property(property="phone_number", type="string", example="123456789"),
     *                     @OA\Property(property="enabled", type="boolean", example=true)
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
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function getAllUsers(Request $request)
    {
        $admin = $this->authenticateAdmin($request);

        $users = User::where('id', '!=', $admin->id)->get();

        return response()->json(['users' => $users], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/user/{id}",
     *     tags={"Admin"},
     *     summary="Delete a user by ID",
     *     description="Allows an admin to delete a user from the system. The admin cannot delete their own account.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User successfully deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User successfully deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized action",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You cannot delete your own user")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found")
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
     *)
     */
    public function deleteUser(Request $request, $id)
    {
        $admin = $this->authenticateAdmin($request);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->id === $admin->id) {
            return response()->json(['message' => 'You cannot delete your own user'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User successfully deleted'], 200);
    }

    /**
     * @OA\Patch(
     *     path="/api/admin/user/{id}",
     *     tags={"Admin"},
     *     summary="Update a user's information",
     *     description="Allows an admin to update a user's details such as name, email, phone number, and password.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="phone_number", type="string", example="123456789"),
     *             @OA\Property(property="new_password", type="string", example="newpassword123"),
     *             @OA\Property(property="enabled", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User successfully updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User successfully updated"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="phone_number", type="string", example="123321111"),
     *                 @OA\Property(property="new_password", type="string", example="password")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication required",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Authentication required. Please log in.")
     *        )
     *    ),
     **    security={{"bearerAuth": {}}}
     **)
     */
    public function updateUser(Request $request, $id)
    {
        $admin = $this->authenticateAdmin($request);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:9',
            'new_password' => 'nullable|string|min:6',
            'enabled' => 'required|boolean',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->phone_number = $validatedData['phone_number'];
        $user->enabled = $validatedData['enabled'];

        if (!empty($validatedData['new_password'])) {
            $user->password = bcrypt($validatedData['new_password']);
        }

        $user->update();

        return response()->json(['message' => 'User successfully updated', 'user' => $user], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/user/create",
     *     tags={"Admin"},
     *     summary="Create a new user",
     *     description="Allows an admin to create a new user in the system.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", example="jane.doe@example.com"),
     *             @OA\Property(property="phone_number", type="string", example="987654321"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User successfully created",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User successfully created"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Jane Doe"),
     *                 @OA\Property(property="email", type="string", example="jane.doe@example.com"),
     *                 @OA\Property(property="phone_number", type="string", example="987654321"),
     *                 @OA\Property(property="profile_picture_path", type="string", 
     *                     example="https://res.cloudinary.com/dxvjedi2n/image/upload/v1736545077/announcements_photos/akfh7cmj35d5ynlmtolk.png"),
     *                 @OA\Property(property="enabled", type="boolean", example=true),
     *                 @OA\Property(property="admin", type="boolean", example=false),
     *                 @OA\Property(property="email_verified_at", type="string", format="date-time", 
     *                     example="2025-01-12T23:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array",
     *                     @OA\Items(type="string", example="The email has already been taken.")
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
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function createUser(Request $request)
    {
        $admin = $this->authenticateAdmin($request);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:9',
            'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->phone_number = $validatedData['phone_number'];
        $user->password = bcrypt($validatedData['password']);
        $user->profile_picture_path = "https://res.cloudinary.com/dxvjedi2n/image/upload/v1736545077/announcements_photos/akfh7cmj35d5ynlmtolk.png";
        $user->enabled = 1;
        $user->admin = 0;

        $user->email_verified_at = now();

        $user->save();

        return response()->json(['message' => 'User successfully created', 'user' => $user], 201);
    }

    public function getAllReviews(Request $request)
    {
        $admin = $this->authenticateAdmin($request);

        $reviews = Review::with(['valuator', 'ratedUser'])->get();

        return response()->json(['reviews' => $reviews], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/reviews",
     *     tags={"Admin"},
     *     summary="Retrieve all reviews",
     *     description="Fetches all reviews from the system, including details about the user who rated and the user who was rated.",
     *     @OA\Response(
     *         response=200,
     *         description="List of reviews retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="reviews", type="array", 
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="rating", type="integer", example=5),
     *                     @OA\Property(property="comment", type="string", example="Great service!"),
     *                     @OA\Property(property="valuator", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="John Doe"),
     *                         @OA\Property(property="email", type="string", example="john.doe@example.com")
     *                     ),
     *                     @OA\Property(property="ratedUser", type="object",
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="name", type="string", example="Jane Smith"),
     *                         @OA\Property(property="email", type="string", example="jane.smith@example.com")
     *                     )
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
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function deleteReview(Request $request, $id)
    {
        $admin = $this->authenticateAdmin($request);

        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        $review->delete();

        return response()->json(['message' => 'Review successfully deleted'], 200);
    }

    protected function authenticateAdmin(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'No token provided'], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json(['message' => 'Authentication required. Please log in.'], 401);
        }

        $user = $accessToken->tokenable;

        if ($user->admin == 0) {
            return response()->json(['message' => 'You do not have permissions to perform this action'], 403);
        }

        return $user;
    }
}
