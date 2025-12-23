<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized: admin only.'], 403);
            }
            abort(403, 'Unauthorized: admin only.');
        }
        return $next($request);
    }
}
