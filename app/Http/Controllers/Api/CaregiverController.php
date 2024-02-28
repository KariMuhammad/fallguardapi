<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CaregiverResource;
use App\Models\Caregiver;
use App\Models\User;
use App\Rules\GenderValidateRule;
use Illuminate\Http\Request;

class CaregiverController extends Controller
{
    public function __construct(){
        $this->middleware('role:caregiver', ['except' => ['register', 'login']]);    
        $this->middleware('check.token:Caregiver', ['only' => ['register', 'login']]);
    }

    // ============================= Caregiver =============================
    // Get all caregivers
    public function index()
    {
        return CaregiverResource::collection(Caregiver::all());
    }

    public function register(Request $request)
    {
        // Register Caregiver
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:caregivers,email',
            // checks if the password contains at least one lowercase letter, one uppercase letter, and one number
            'password' => 'required|string|big_password|min:8',
            "date_of_birth" => "sometimes|required|date",
            'phone' => 'required|string|regex:/^01[0-2]{1}[0-9]{8}$/',
            "gender" => ["required", new GenderValidateRule],
            "country" => "sometimes|required|string|max:255",
            'address' => 'sometimes|required|string|max:255',
            'photo' => 'sometimes|required|file|max:255',
        ]);

        $caregiver = Caregiver::create($request->all());

        return response()->json(new CaregiverResource($caregiver), 201);
    }

    public function login(Request $request) {
        // Login Caregiver
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'required|string'
        ]);

        $caregiver = Caregiver::where('email', $request->email)->first();

        if (!$caregiver || !\Hash::check($request->password, $caregiver->password)) {
            return response()->json([
                "errors" => [
                    'message' => 'The provided credentials are incorrect.'
                ]
            ], 401);
        }
        $token = $caregiver->createToken($request->device_name, ['*'])->plainTextToken;

        return response()->json([
            "token" => $token,
            "user" => new CaregiverResource($caregiver)
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            "message" => "Logged out"
        ]);
    }

    public function me(Request $request)
    {
        if ($request->query('deep') === 'true')
            $request->user()->load('patients');

        return response()->json([
            "data" => new CaregiverResource($request->user())
        ]);
    }

    // ============================= Relationships =============================

    // Get all patients which caregiver is following
    public function patients(Request $request)
    {
        return response()->json([
            "data" => $request->user()->patients
        ]);
    }

    // Get a specific patient which caregiver is following
    public function patient(Request $request, $id)
    {
        $patient = $request->user()->patients()->find($id);

        if (!$patient) {
            return response()->json([
                "errors" => [
                    "message" => "Patient not found"
                ]
            ], 404);
        }

        return response()->json([
            "data" => $patient
        ]);
    }

    // Get all contacts for a specific patient
    public function contacts(Request $request, $id)
    {
        $patient = $request->user()->patients()->find($id);

        if (!$patient) {
            return response()->json([
                "errors" => [
                    "message" => "Patient not found"
                ]
            ], 404);
        }

        return response()->json([
            "data" => $patient->contacts
        ]);
    }

    // Get a specific contact for a specific patient
    public function contact(Request $request, $id, $contact_id)
    {
        $patient = $request->user()->patients()->find($id);

        if (!$patient) {
            return response()->json([
                "errors" => [
                    "message" => "Patient not found"
                ]
            ], 404);
        }

        $contact = $patient->contacts()->find($contact_id);

        if (!$contact) {
            return response()->json([
                "errors" => [
                    "message" => "Contact not found"
                ]
            ], 404);
        }

        return response()->json([
            "data" => $contact
        ]);
    }

    // Get all falls for a specific patient
    public function falls(Request $request, $id)
    {
        $patient = $request->user()->patients()->find($id);

        if (!$patient) {
            return response()->json([
                "errors" => [
                    "message" => "Patient not found"
                ]
            ], 404);
        }

        return response()->json([
            "data" => $patient->falls()->orderBy('created_at', 'desc')->get()
        ]);
    }


    // ============================= Follow System =============================
    // Follow System
    public function follow(Request $request, $id)
    {
        $patient = User::find($id);

        if (!$patient) {
            return response()->json([
                "errors" => [
                    "message" => "Patient not found"
                ]
            ], 404);
        }
        // Check if Caregiver already following the patient
        if ($request->user()->patients()->find($id)) {
            return response()->json([
                "errors" => [
                    "message" => "Caregiver already following the patient"
                ]
            ], 400);
        }

        $request->user()->patients()->attach($patient);

        return response()->json([
            "message" => "Patient followed successfully"
        ]);
    }


    // Unfollow System
    public function unfollow(Request $request, $id)
    {
        $patient = User::find($id);

        if (!$patient) {
            return response()->json([
                "errors" => [
                    "message" => "Patient not found"
                ]
            ], 404);
        }

        // Check if Caregiver already unfollowing the patient
        if(!$request->user()->patients()->find($id)) {
            return response()->json([
                "errors" => [
                    "message" => "Caregiver already unfollowing the patient"
                ]
            ], 400);
        }
            
        $request->user()->patients()->detach($patient);

        return response()->json([
            "message" => "Patient unfollowed successfully"
        ]);
    }
}
