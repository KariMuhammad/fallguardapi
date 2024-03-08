<?php

namespace App\Http\Controllers\Api\Auth\Core;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request) {
        if (!$request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ]);
        }

        $request->validate([
            "email" => "required|email|exists:users,email",
            "otp" => "required|numeric|max:6"
        ]);

        $user = User::where('email', $request->email)->first();

        $user->email_verified_at = now();

        $user->save();

        return response()->json([
            'message' => 'Email verified'
        ]);
    }
}
