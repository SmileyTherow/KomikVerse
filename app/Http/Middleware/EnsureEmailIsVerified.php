<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user || !$user->email_verified_at) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Email belum terverifikasi.'], 403);
            }
            return redirect()->route('otp.verify.show')->withErrors(['email' => 'Email belum terverifikasi.']);
        }

        return $next($request);
    }
}
