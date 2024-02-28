<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('role:patient', [
            'only' => ['me', 'update']
        ]);

        $this->middleware('check.token:Patient', [
            'only' => ['register', 'login']
        ]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new UserCollection(User::paginate(4));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        // Register User
        $request->validate([
            'name' => 'required|string|max:255',
            "family_name" => "required|string|max:255",
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            "date_of_birth" => "required|date",
            'phone' => 'required|string|regex:/^01[0-2]{1}[0-9]{8}$/',
            "country" => "required|string|max:255",
            'address' => 'required|string|max:255',
            'photo' => 'sometimes|required|file|max:255',
        ]);

        $user = User::create($request->except(["photo"]));

        return response()->json(new UserResource($user), 201);
    }

    public function login(Request $request) {
        // Login User
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json([
                "errors" => [
                    'message' => 'The provided credentials are incorrect.'
                ]
            ], 401);
        }
        $token = $user->createToken("token_web", ['*'])->plainTextToken;

        return response()->json([
            "token" => $token,
            "user" => new UserResource($user)
        ])->header("Set-Cookie", "token=$token; SameSite=None; Secure; HttpOnly; path=/; domain=localhost; max-age=1209600; samesite=none; secure; httponly");
    }

    /**
     * Display the specified resource.
     */
    public function me(Request $request)
    {
        return response()->json([
            "data" => new UserResource($request->user())
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Update User Profile
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            "family_name" => "sometimes|required|string|max:255",
            'email' => 'sometimes|required|email|unique:users,email',
            "date_of_birth" => "sometimes|required|date",
            'phone' => 'sometimes|required|string|max:255',
            "country" => "sometimes|required|string|max:255",
            'address' => 'sometimes|required|string|max:255',
            'photo' => 'sometimes|required|file|max:255',
        ]);

        foreach($request->all() as $key => $value) {
            if ($key !== 'photo')
                $user->{$key} = $value;
        }

        if ($request->hasFile('photo')) {
            $user->photo = $request->file('photo')->store('photos');
        }

        if ($user->isDirty()) {
            $user->save();
            return response()->json(new UserResource($user), 200);
        }else {
            return response()->json([
                "errors" => [
                    "message" => "No changes detected"
                ]
            ], 422);
        }
    }

    // Get all contacts for a user
    public function contacts(Request $request, string $id) {
        $user = User::findOrFail($id);
        return response()->json([
            'user_id' => $user->id,
            "data" => $user->contacts
        ]);
    }

    // Get Single Contact
    public function contact(Request $request, string $id, string $contact_id) {
        $user = User::findOrFail($id);
        $contact = $user->contacts()->findOrFail($contact_id);
        
        return response()->json([
            'user_id' => $user->id,
            "data" => $contact
        ]);
    }

    // Get falls for a user
    public function falls(Request $request, string $id) {
        $user = User::findOrFail($id);

        return response()->json([
            "data" => $user->falls
        ]);
    }

    // Get Single Patient
    public function show(Request $request, string $id) {
        $user = User::findOrFail($id);
        // return $request->query('deep');

        if ($request->query('deep') === 'true') {
            $user = $user->load('contacts', 'falls');
            return $user;
        }

        return response()->json([
            "data" => new UserResource($user)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        
        return response()->json([
            "message" => "Logged out successfully"
        ]);
    }
}
