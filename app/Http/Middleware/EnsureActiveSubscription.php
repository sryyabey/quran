<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasActiveSubscription()) {
            return response()->json([
                'message' => 'Active subscription is required.',
            ], 402);
        }

        return $next($request);
    }
}
