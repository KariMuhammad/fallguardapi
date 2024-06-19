<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface AuthenticationInterface
{
    public function register(Request $request);
    public function login(Request $request);
    public function verifyEmail(Request $request);
    public function forgotPassword(Request $request);
    public function resetPassword(Request $request);
    public function logout(Request $request);
}
