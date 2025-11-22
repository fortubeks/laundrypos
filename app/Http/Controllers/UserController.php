<?php
namespace App\Http\Controllers;

use App\Constants\ApiConstants;
use App\Helpers\ApiHelper;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function get_user(Request $request)
    {
        try {
            // $user_id = auth()->user()->id;
            $user = $request->user();

            $user = User::findOrFail($user->id);

            return ApiHelper::validResponse('user retrieved successfully!', $user);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('User not found', ApiConstants::SERVER_ERR_CODE);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'name'  => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
        ]);

        $user->fill($validatedData);
        $user->save();

        return ApiHelper::validResponse('Profile updated successfully!', $user);
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!password_verify($validatedData['old_password'], $user->password)) {
            return ApiHelper::problemResponse('Old password is incorrect', ApiConstants::VALIDATION_ERR_CODE);
        }

        $user->password = bcrypt($validatedData['new_password']);
        $user->save();

        return ApiHelper::validResponse('Password changed successfully!', null);
    }

    public function delete_user(Request $request)
    {
        $user = $request->user();

        try {
            $user->delete();
            return ApiHelper::validResponse('User deleted successfully!', null);
        } catch (\Exception $e) {
            return ApiHelper::problemResponse('Failed to delete user', ApiConstants::SERVER_ERR_CODE);
        }
    }
}
