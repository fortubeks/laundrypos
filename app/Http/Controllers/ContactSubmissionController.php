<?php
namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class ContactSubmissionController extends Controller
{
    public function store(Request $request)
    {
        $key = 'contact-us:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'message' => 'Too many submissions. Try again later.',
            ], 429);
        }

        RateLimiter::hit($key, 60);

        $validated = $request->validate([
            'name'         => 'required|string|max:120',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:30',
            'message'      => 'required|string|max:3000',
            'captcha'      => 'required|string',
            'company_name' => 'nullable|string|max:255',
        ]);

        if (! empty($validated['company_name'])) {
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

        $domain = strtolower((string) substr(strrchr($validated['email'], "@"), 1));

        if (in_array($domain, $blockedDomains, true)) {
            return response()->json([
                'message' => 'Disposable emails are not allowed.',
            ], 422);
        }

        $turnstile = Http::asForm()->post(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            [
                'secret'   => env('TURNSTILE_SECRET_KEY'),
                'response' => $validated['captcha'],
                'remoteip' => $request->ip(),
            ]
        );

        if (! data_get($turnstile->json(), 'success')) {
            return response()->json([
                'message' => 'Captcha verification failed.',
            ], 422);
        }

        ContactSubmission::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'] ?? null,
            'message'    => $validated['message'],
            'source'     => 'website',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return ApiHelper::validResponse('Message sent successfully. Our team will contact you shortly.', null);
    }
}
