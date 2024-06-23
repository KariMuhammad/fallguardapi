<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $user = Socialite::driver('google')->user();

        $userExisted = User::where('provider_id', $user->id)->first();

        if( $userExisted ) {
            $token = $userExisted->createToken($request->userAgent(), ['*'])->plainTextToken;

            return response()->json([
                'message' => 'Logged in',
                'status' => true,
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        }else {
            $newUser = User::create([
                'name' => $user->name,
                'phone' => "+20", // How to get the phone number from google?
                'email' => $user->email,
                'password' => Hash::make($user->id),
                'provider_id' => $user->id,
                'provider' => 'google',
            ]);

            $token = $newUser->createToken($request->userAgent(), ['*'])->plainTextToken;

            return response()->json([
                'message' => 'Logged in',
                'status' => true,
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        }
    }
}