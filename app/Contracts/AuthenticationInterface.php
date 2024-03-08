<?php

namespace App\Contracts;

interface AuthenticationInterface
{
    public function register($request);
    public function login($request);
    public function verifyEmail($request);
    public function forgotPassword($request);
    public function resetPassword($request);
    public function logout($request);
}
