<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendFollowupEmailJob;
use App\Models\SocialLogin;
use App\Models\User;
use Google\Client as GoogleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    public function handleCallback(Request $request)
    {
        try {

            $request->validate([
                'provider_token' => 'required|string',
                'provider'       => 'required|string',
            ]);

            if ($request->provider !== 'google') {
                return response()->json(['error' => 'Unsupported provider'], 400);
            }

            // ✅ Verify Google ID token
            $client = new GoogleClient([
                'client_id' => config('services.google.client_id'),
            ]);

            $payload = $client->verifyIdToken($request->provider_token);

            if (! $payload) {
                return response()->json(['error' => 'Invalid Google token'], 401);
            }

            $email      = $payload['email'] ?? null;
            $name       = $payload['name'] ?? 'Google User';
            $providerId = $payload['sub'];

            if (! $email) {
                return response()->json(['error' => 'Email not provided'], 422);
            }

            // ✅ Find existing social login
            $socialLogin = SocialLogin::where('provider', 'google')
                ->where('provider_id', $providerId)
                ->first();

            if ($socialLogin) {
                $user = $socialLogin->user;
            } else {

                $user = User::firstOrCreate([
                    'name'              => $name,
                    'password'          => Hash::make(Str::random(16)),
                    'email'             => $email,
                    'phone'             => '0000000000',
                    'email_verified_at' => now(),
                    'role'              => 'Super Admin',
                ]);

                SocialLogin::create([
                    'user_id'     => $user->id,
                    'provider'    => 'google',
                    'provider_id' => $providerId,
                ]);

                $user->user_account_id = $user->id;
                $user->save();

                SendFollowupEmailJob::dispatch($user, 1)->delay(now()->addWeek(1));
                SendFollowupEmailJob::dispatch($user, 2)->delay(now()->addWeek(2));
                SendFollowupEmailJob::dispatch($user, 3)->delay(now()->addWeek(3));

                // Create social login record
            }

            Auth::login($user);

            $user->update(['last_login' => now()]);

            $token = $user->createToken('myapptoken')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user'  => $user,
            ]);

        } catch (\Exception $e) {

            Log::error('Google Auth Error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Authentication failed',
            ], 500);
        }
    }
}
