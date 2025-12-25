<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->email_verified_at) {
            session()->flash('warning', 'Silakan verifikasi email terlebih dahulu.');
            return redirect()->route('otp.verify.show');
        }

        return $next($request);
    }
}
