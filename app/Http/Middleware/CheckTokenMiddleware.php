<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Check if user is already logged in
        $bearerToken = $request->bearerToken();

        if ($bearerToken) {
            $token = \Laravel\Sanctum\PersonalAccessToken::findToken($bearerToken);
            if ($token && $token->tokenable_type === "App\\Models\\$role") {
                return response()->json([
                    "errors" => [
                        'message' => '"You already Logged in! Please logout first.'
                    ]
                ], 400);
            }
        }
        return $next($request);
    }
}
