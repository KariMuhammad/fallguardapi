<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Caregiver;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function followPatient(Request $request)
    {
        $caregiverId = auth()->user()->id;
        $patientId = $request->input('patient_id');

        // Check if the caregiver is not already following the patient
        if (!Caregiver::find($caregiverId)->patients()->where('id', $patientId)->exists()) {
            Caregiver::find($caregiverId)->patients()->attach($patientId);
        }

        return response()->json([
            "message" => "Patient followed successfully"
        ]);
    }

    public function unfollowPatient(Request $request)
    {
        $caregiverId = auth()->user()->id;
        $patientId = $request->input('patient_id');

        Caregiver::find($caregiverId)->patients()->detach($patientId);
        
        return response()->json([
            "message" => "Patient unfollowed successfully"
        ]);
    }
}
