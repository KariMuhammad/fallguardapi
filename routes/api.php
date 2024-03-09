<?php

use App\Http\Controllers\Api\EmergencyContactController;
use App\Http\Controllers\Api\FallController;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ======================= Shared Routes ===================
Route::middleware(['auth:sanctum', 'check.role'])->group(function () {
    Route::prefix('me')->group(function () { 
        Route::get('/', function (Request $request) {
            if ($request->query('deep') == 'true') {
                if ($request->user()->role == "caregiver") {
                    $request->user()->load('patients', 'patients.contacts', 'patients.falls');
                }
            }
            
            return response()->json([
                "data" => new UserResource($request->user())
            ]);
        });

        // Follow-ups
        // Caregiver can follow many patients, but patients cannot follow anyone
        Route::post('follow/{id}', [App\Http\Controllers\Api\CaregiverController::class, 'follow']);
        Route::post('unfollow/{id}', [App\Http\Controllers\Api\CaregiverController::class, 'unfollow']);
    });

    // Emergency Contacts
    Route::apiResource('emergency-contacts', EmergencyContactController::class);
    // Falls
    Route::apiResource('falls', FallController::class);
    Route::get('falls/{id}/user', [FallController::class, 'user']);
});

//======================= Patients =======================
Route::prefix('patients')->group(function () {
    Route::middleware("auth:sanctum")->group(function () {
        Route::get('/', [App\Http\Controllers\Api\UserController::class, 'index']); // Get all users
        
        Route::prefix("{id}")->group(function() {
            Route::get('/', [App\Http\Controllers\Api\UserController::class, 'show'])->where([
                "id" => "[0-9]+"
            ]); // Get a specific user
    
            Route::get('/contacts', [App\Http\Controllers\Api\UserController::class, 'contacts'])->where([
                "id" => "[0-9]+"
            ]); // Get all contacts for a user
    
            Route::get('/contacts/{contact_id}', [App\Http\Controllers\Api\UserController::class, 'contact'])->where([
                "id" => "[0-9]+"
            ]); // Get specific contacts for a user
    
            Route::get('/falls', [App\Http\Controllers\Api\UserController::class, 'falls'])->where([
                "id" => "[0-9]+"
            ]); // Get all falls for a user
        });

        Route::prefix('me')->group(function() {
            Route::get('/', [App\Http\Controllers\Api\UserController::class, 'me']); // Get the current user
            Route::post('logout', [App\Http\Controllers\Api\UserController::class, 'logout']); // Logout a user
        });
    });
});

//======================= Caregivers =======================
Route::prefix('caregivers')->middleware("check.accept")->group(function () {    
    Route::middleware(['auth:sanctum', 'check.role'])->group(function () {
        Route::get('/', [App\Http\Controllers\Api\CaregiverController::class, 'index']);
        
        Route::prefix('me')->group(function () {
            Route::get('/', [App\Http\Controllers\Api\CaregiverController::class, 'me']);
            Route::post('logout', [App\Http\Controllers\Api\CaregiverController::class, 'logout']);
            
            Route::get('patients', [App\Http\Controllers\Api\CaregiverController::class, 'patients']);
            Route::get('patients/{id}', [App\Http\Controllers\Api\CaregiverController::class, 'patient']);

            Route::get('patients/{id}/contacts', [App\Http\Controllers\Api\CaregiverController::class, 'contacts']);
            Route::get('patients/{id}/contacts/{contact_id}', [App\Http\Controllers\Api\CaregiverController::class, 'contact']);

            Route::get('patients/{id}/falls', [App\Http\Controllers\Api\CaregiverController::class, 'falls']);
        });
    });
});

//======================= Authentication =======================
Route::middleware('guest:sanctum')->prefix("auth")->group(function () {
    Route::post('register', [\App\Services\AuthService::class, 'register']); // Register a new user
    Route::post('login', [\App\Services\AuthService::class, 'login']); // Login a user
    
    // Verify Email
    Route::post('verify-email', [\App\Services\AuthService::class, 'verifyEmail']);
    Route::post('resend-code', [\App\Services\AuthService::class, 'resendOtp']);
    
    // Reset Password
    Route::post('forgot-password', [\App\Services\AuthService::class, 'forgotPassword']);
    Route::post('reset-password', [\App\Services\AuthService::class, 'resetPassword']);
});

