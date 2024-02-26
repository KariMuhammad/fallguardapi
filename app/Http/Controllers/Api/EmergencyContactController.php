<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmergencyContact;
use Illuminate\Http\Request;

class EmergencyContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',

            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',

            "address" => "required|string|max:255",

            "relationship" => "required|string|max:255",
        ]);

        $emergency_contact = EmergencyContact::create([
            ...$request->except(["first_name", "last_name"]),
            "user_id" => $request->user()->id,
            "name" => "{$request->first_name} {$request->last_name}"
        ]);

        return response()->json([
            "data" => $emergency_contact,
        ], 201);
    }

    // may no emergency contact, prefer used 'id' instead
    public function show(EmergencyContact $emergency_contact)
    {
        return response()->json([
            "data" => $emergency_contact,
        ], 200);
    }

    public function update(Request $request, EmergencyContact $emergency_contact)
    {
        $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',

            'phone' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',

            "relationship" => "sometimes|required|string|max:255",
        ]);

        foreach($request->all() as $key => $value) {
            $emergency_contact->{$key} = $value;
        }

        if ($emergency_contact->isDirty()) {
            $emergency_contact->save();
        }else {
            return response()->json([
                "message" => "No changes made",
            ], 422);
        }

        return response()->json([
            "data" => $emergency_contact,
        ], 200);
    }

    public function destroy(string $id)
    {
        $emergency_contact = EmergencyContact::find($id);

        if (!$emergency_contact) {
            return response()->json([
                "message" => "Emergency contact not found",
            ], 404);
        }

        return response()->json([
            "message" => "Emergency contact deleted successfully",
        ], 200);
    }

    public function getEmergencyContactsByUserId(Request $request)
    {
        $emergency_contact = EmergencyContact::where("user_id", $request->query("user_id"))->get();
    
        if (!$emergency_contact) {
            return response()->json([
                "message" => "Emergency contacts not found",
            ], 404);
        }

        return response()->json([
            "data" => $emergency_contact,
        ], 200);
    }
}
