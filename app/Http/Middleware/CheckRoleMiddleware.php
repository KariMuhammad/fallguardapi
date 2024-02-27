<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Check if role of current user as passed in the request
        if ($request->role !== $role) {
            return response()->json([
                "errors" => [
                    "message" => "Your role not unauthorized to do actions on this endpoint!",
                ]
            ], 401);
        }

        return $next($request);
    }
}
