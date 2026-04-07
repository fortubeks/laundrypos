<?php
namespace App\Http\Controllers\Auth;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Jobs\SendFollowupEmailJob;
use App\Mail\EmailVerificationOtp;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        $key = 'register:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'message' => 'Too many registration attempts. Try again later.',
            ], 429);
        }

        RateLimiter::hit($key, 60);
        Log::info($request->all());

        $turnstile = Http::asForm()->post(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            [
                'secret'   => env('TURNSTILE_SECRET_KEY'),
                'response' => $request->captcha,
                'remoteip' => $request->ip(),
            ]
        );
        Log::info('Turnstile Response: ' . $turnstile->body());

        if (! data_get($turnstile->json(), 'success')) {
            return response()->json([
                'message' => 'Captcha verification failed.',
            ], 422);
        }

        if ($request->filled('company_name')) {
            return response()->json([
                'message' => 'Invalid submission.',
            ], 422);
        }

        $blockedDomains = [
            'mailinator.com',
            '10minutemail.com',
            'guerrillamail.com',
            'yopmail.com',
        ];

        $domain = substr(strrchr($request->email, "@"), 1);

        if (in_array($domain, $blockedDomains)) {
            return response()->json([
                'message' => 'Disposable emails are not allowed.',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:80',
            'email'           => 'required|email|unique:users,email',
            'phone'           => 'required|string|max:20',
            'password'        => 'required|string|min:6',
            'referral_source' => 'nullable|string|in:google_ads,instagram,facebook,friend,other',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        try {
            $otp = rand(100000, 999999);

            $user = User::create([
                'name'            => $request->name,
                'email'           => $request->email,
                'phone'           => $request->phone,
                'password'        => Hash::make($request->password),
                'role'            => 'Super Admin',
                'referral_source' => $request->referral_source,
                'otp'             => $otp,
                'otp_expires_at'  => now()->addMinutes(10),
            ]);

            $user->user_account_id = $user->id;
            $user->save();

            Mail::to($user->email)->send(new EmailVerificationOtp($otp));

            SendFollowupEmailJob::dispatch($user, 1)->delay(now()->addWeek(1));
            SendFollowupEmailJob::dispatch($user, 2)->delay(now()->addWeek(2));
            SendFollowupEmailJob::dispatch($user, 3)->delay(now()->addWeek(3));

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
            'otp'   => 'required|string|max:6',
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (! $user) {
                return ApiHelper::problemResponse('User not found.', 404);
            }

            if ($user->otp !== $request->otp) {
                return ApiHelper::problemResponse('Invalid OTP.', 422);
            }

            if ($user->otp_expires_at && now()->greaterThan($user->otp_expires_at)) {
                return ApiHelper::problemResponse('OTP expired.', 422);
            }

            $user->email_verified_at = now();
            $user->otp               = null;
            $user->otp_expires_at    = null;
            $user->save();

            return ApiHelper::validResponse('Email verified successfully.', null);
        } catch (\Exception $e) {
            Log::error('Email Verification Error: ' . $e->getMessage());
            return ApiHelper::problemResponse($e->getMessage(), 500);
        }
    }

    public function resend_otp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {

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

            $user->otp            = $otp;
            $user->otp_expires_at = now()->addMinutes(10);
            $user->save();

            // Send email
            Mail::to($user->email)->send(new EmailVerificationOtp($otp));

            return ApiHelper::validResponse('A new verification code has been sent to your email.', null);
        } catch (\Exception $e) {
            Log::error('Resend OTP Error: ' . $e->getMessage());
            return ApiHelper::problemResponse($e->getMessage(), 500);
        }
    }

    public function initializeUserSettings($user)
    {
        if (! Setting::where('user_id', '=', $user->id)->exists()) {
            // setings not found
            //create and store new app setting and then redirect to page
            $setting                   = new Setting;
            $setting->user_id          = $user->id;
            $setting->sms_api_key      = 'a13babcd7b8dea714c3454f865f97d36ab76fbde';
            $setting->sms_api_username = 'fortubeks2010@hotmail.com';
            $setting->save();
        }
    }

}
