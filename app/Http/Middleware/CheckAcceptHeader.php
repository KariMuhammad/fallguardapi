<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAcceptHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->acceptsJson()) {
            return response()->json([
                "errors" => [
                    "message" => "`Accept` header must be set to `application/json`"
                ]
                ], 406);
                // 406 Not Acceptable
        }
        return $next($request);
    }
}
