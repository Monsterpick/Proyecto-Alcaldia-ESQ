<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle an incoming authentication request (API).
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
                'remember' => 'boolean',
            ]);

            $this->ensureIsNotRateLimited($request);

            if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
                RateLimiter::hit($this->throttleKey($request));

                return response()->json([
                    'message' => __('auth.failed'),
                    'errors' => [
                        'email' => __('auth.failed'),
                    ],
                ], 422);
            }

            RateLimiter::clear($this->throttleKey($request));
            $request->session()->regenerate();

            $user = Auth::user();
            $isBeneficiario = $user->hasRole(['Beneficiario']);

            return response()->json([
                'success' => true,
                'is_beneficiario' => $isBeneficiario,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        $key = $this->throttleKey($request);

        if (!RateLimiter::tooManyAttempts($key, 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($key);

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->email) . '|' . $request->ip());
    }
}
