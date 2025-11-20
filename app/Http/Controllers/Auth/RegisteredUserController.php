<?php
namespace App\Http\Controllers\Auth;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:80',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        try {
            $otp = rand(100000, 999999);

            $user = User::create([
                'name'           => $request->name,
                'email'          => $request->email,
                'password'       => Hash::make($request->password),
                'role'           => 'Super Admin',
                'remember_token' => $otp,
                'otp_expires_at' => now()->addMinutes(10),
            ]);

            Mail::to($user->email)->send(new EmailVerificationOtp($otp));

            return ApiHelper::validResponse('Registration successful — verify your email.', null);
        } catch (\Exception $e) {
            Log::error('Registration Error: ' . $e->getMessage());
            return ApiHelper::problemResponse($e->getMessage(), 500);
        }
    }

    public function verify_email(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return ApiHelper::problemResponse('User not found.', 404);
        }

        if ($user->remember_token !== $request->otp) {
            return ApiHelper::problemResponse('Invalid OTP.', 422);
        }

        if ($user->otp_expires_at && now()->greaterThan($user->otp_expires_at)) {
            return ApiHelper::problemResponse('OTP expired.', 422);
        }

        $user->email_verified_at = now();
        $user->remember_token    = null;
        $user->otp_expires_at    = null;
        $user->save();

        return ApiHelper::validResponse('Email verified successfully.', null);
    }

    public function resend_otp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return ApiHelper::problemResponse('User not found.', 404);
        }

        if ($user->email_verified_at !== null) {
            return ApiHelper::problemResponse('Email is already verified.', 422);
        }

        // Optional: prevent spam (resend allowed every 60 seconds)
        if ($user->otp_expires_at && now()->lessThan($user->otp_expires_at->subMinutes(9))) {
            return ApiHelper::problemResponse('Please wait before requesting another OTP.', 429);
        }

        // Generate new OTP
        $otp = rand(100000, 999999);

        $user->remember_token = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        // Send email
        Mail::to($user->email)->send(new EmailVerificationOtp($otp));

        return ApiHelper::validResponse('A new verification code has been sent to your email.', null);
    }

}
