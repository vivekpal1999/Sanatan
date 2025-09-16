<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureGuestApi
{
    public function handle(Request $request, Closure $next)
    {
        // Agar user already logged-in hai, toh registration allowed nahi hai
        if ($request->user()) {
            return response()->json([
                'message' => '.'
            ], 403);
        }

        return $next($request);
    }
}
