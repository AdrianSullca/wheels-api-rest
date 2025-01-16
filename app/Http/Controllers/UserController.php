<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ProfileUpdateGeneralInformationRequest;
use App\Http\Requests\Auth\ProfileUpdateSecurityRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user",
     *     tags={"User"},
     *     summary="Retrieve user details by token",
     *     description="Fetches the authenticated user's details using the provided bearer token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="your_access_token_here"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com")
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
    public function getUserByToken(Request $request)
    {
        $user = $this->authenticateUser($request);

        return response()->json(['user' => $user], 200);
    }

    /**
     * @OA\Patch(
     *     path="/api/user/updateGeneralInformation",
     *     tags={"User"},
     *     summary="Update general information of the authenticated user",
     *     description="Allows a user to update their general profile information, including name, email, phone number, and profile picture.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="phone_number", type="string", example="123456789"),
     *             @OA\Property(property="new_profile_picture", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Profile updated successfully"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="phone_number", type="string", example="123456789"),
     *                 @OA\Property(property="profile_picture_path", type="string", example="https://res.cloudinary.com/example/image/upload/v123456789/profile.jpg")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while updating the profile",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An error occurred while updating the profile."),
     *             @OA\Property(property="error", type="string", example="Error details here")
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
    public function updateGeneralInformation(ProfileUpdateGeneralInformationRequest $request)
    {
        try {
            $user = $this->authenticateUser($request);

            $validatedData = $request->validated();

            if ($request->hasFile('new_profile_picture')) {
                if ($user->profile_picture_path && $user->profile_picture_path !== 'https://res.cloudinary.com/dxvjedi2n/image/upload/v1736552760/profile_pictures/kbtqw3i0j0ylriivop6b.png') {
                    $oldPublicId = $this->getPublicIdFromUrl($user->profile_picture_path);
                    if ($oldPublicId) {
                        Cloudinary::destroy($oldPublicId);
                    }
                }
                $cloudinaryImage = $request->file('new_profile_picture')->storeOnCloudinary('profile_pictures');
                $validatedData['profile_picture_path'] = $cloudinaryImage->getSecurePath();
            }

            $user->update($validatedData);

            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the profile.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/user/updateSecurity",
     *     tags={"User"},
     *     summary="Update user's security information",
     *     description="Allows a user to update their password by providing the current password and a new password.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="current_password", type="string", example="currentPassword123"),
     *             @OA\Property(property="new_password", type="string", example="newPassword456"),
     *             @OA\Property(property="password_confirmation", type="string", example="newPassword456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Password updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="current_password", type="array",
     *                     @OA\Items(type="string", example="The current password is incorrect")
     *                 ),
     *                 @OA\Property(property="new_password", type="array",
     *                     @OA\Items(type="string", example="The new password must be different from the current password")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while updating the password",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred while updating the password."),
     *             @OA\Property(property="error", type="string", example="Error details here")
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
    public function updateSecurity(ProfileUpdateSecurityRequest $request)
    {
        try {
            $user = $this->authenticateUser($request);

            $user->password = Hash::make($request->input('new_password'));
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the password.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getPublicIdFromUrl($url)
    {
        $parts = explode('/', $url);
        $filename = end($parts);
        return pathinfo($filename, PATHINFO_FILENAME);
    }
}
