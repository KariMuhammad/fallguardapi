<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = "";
        // Check which kind of user is logged in
        if ($request->user() instanceof User) {
            $role = "patient";
        }else {
            $role = "caregiver";
        }

        $request->user()->role = $role;

        $request->merge([
            "role" => $role
        ]);

        return $next($request);
    }
}
