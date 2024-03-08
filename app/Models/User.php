<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Resources\User\UserResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        "role",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Get All Emergency Contacts which belongs to the user
    public function contacts() {
        return $this->hasMany(EmergencyContact::class);
    }

    // Get All Falls which belongs to the user
    public function falls() {
        return $this->hasMany(Fall::class);
    }

    public function caregiver() {
        return $this->belongsToMany(Caregiver::class);
    }

    public function getRoleAttribute() {
        return "patient";
    }

    // Get All Notifications which belongs to the user
    // public function notifications() {
    //     return $this->hasMany(Notification::class);
    // }

    public function toArray() {
        return new UserResource($this);
    }

    // The Validators for the User
    public static function validators() {
        return [
            'name' => 'required|string|max:255',
            "family_name" => "required|string|max:255",
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            "date_of_birth" => "required|date",
            'phone' => 'required|string|regex:/^01[0-2]{1}[0-9]{8}$/',
            "country" => "required|string|max:255",
            'address' => 'required|string|max:255',
            'photo' => 'sometimes|required|file|max:255',
        ];
    }
}
