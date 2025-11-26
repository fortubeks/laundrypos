<?php
namespace App\Http\Controllers\Auth;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $this->ensureIsNotRateLimited($request);

        if (! Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        $user = $request->user();

        // ❗ BLOCK UNVERIFIED USERS
        if (! $user->email_verified_at) {
            Auth::logout();
            $otp = rand(100000, 999999);
            $user->otp            = $otp;
            $user->otp_expires_at = now()->addMinutes(10);
            $user->save();

            Mail::to($user->email)->send(new EmailVerificationOtp($otp));

            return ApiHelper::problemResponse('Please verify your email before logging in.', 403);
        }

        // Optional: block inactive users
        if (! $user->is_active) {
            return ApiHelper::problemResponse('Your account is inactive. Contact support.', 403);
        }

        $user->forceFill(['last_login_at' => now()])->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiHelper::validResponse('Login successful', [
            'token' => $token,
            'user'  => $user,
        ]);
    }

    protected function ensureIsNotRateLimited(Request $request)
    {
        $request = $request ?? request();

        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => ["Too many login attempts. Try again in $seconds seconds."],
            ]);
        }
    }

    protected function throttleKey(Request $request)
    {
        $request = $request ?? request();
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

    public function logout(Request $request)
    {
        $request = $request ?? request();
        $request->user()->currentAccessToken()->delete();

        return ApiHelper::validResponse('Logged out', null);
    }

    public function forgot_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return ApiHelper::problemResponse('No account found with this email.', 404);
        }

        $otp = rand(100000, 999999);

        $user->otp            = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        Mail::to($user->email)->send(new EmailVerificationOtp($otp));

        return ApiHelper::validResponse(
            'A password reset code has been sent to your email.',
            null
        );
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

        // Generate new OTP
        $otp = rand(100000, 999999);

        $user->otp            = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        // Send email
        Mail::to($user->email)->send(new EmailVerificationOtp($otp));

        return ApiHelper::validResponse('A new verification code has been sent to your email.', null);
    }

    public function reset_password(Request $request)
    {
        $request->validate([
            'email'        => 'required|email',
            'otp'          => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return ApiHelper::problemResponse('User not found.', 404);
        }

        // OTP validation
        if ($user->otp !== $request->otp) {
            return ApiHelper::problemResponse('Invalid OTP.', 422);
        }

        if ($user->otp_expires_at && now()->greaterThan($user->otp_expires_at)) {
            return ApiHelper::problemResponse('OTP expired.', 422);
        }

        // Update password
        $user->password       = Hash::make($request->new_password);
        $user->otp            = null;
        $user->otp_expires_at = null;
        $user->save();

        return ApiHelper::validResponse('Password has been reset successfully.', null);
    }

}
