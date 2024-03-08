<?php

namespace App\Services;

use App\Contracts\AuthenticationInterface;

class CaregiverService implements AuthenticationInterface
{
    public function register($request)
    {
        // Register Caregiver
        $validated = $request->validate([]);
    }

    public function login($request)
    {
        // Login Caregiver
        $validated = $request->validate([]);
    }

    public function verifyEmail($request)
    {
        // Verify Caregiver Email
        $validated = $request->validate([]);
    }

    public function forgotPassword($request)
    {
        // Forgot Caregiver Password
        $validated = $request->validate([]);
    }

    public function resetPassword($request)
    {
        // Reset Caregiver Password
        $validated = $request->validate([]);
    }

    public function logout($request)
    {
        // Logout Caregiver
        $validated = $request->validate([]);
    }

}