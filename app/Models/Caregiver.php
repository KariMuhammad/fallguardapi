<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Caregiver extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [
        "id",
        "created_at",
        "updated_at",
    ];

    protected $hidden = [
        "password",
        "remember_token",
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

    public function patients()
    {
        return $this->belongsToMany(User::class); // A caregiver has many patients
        /**
         * How to use this relationship:
         * 
         * What is generated sql raw for this relation?
         * > select * from `users` inner join `caregiver_user` on `users`.`id` = `caregiver_user`.`user_id` where `caregiver_user`.`caregiver_id` = ?
         * 
         * What is the name of the pivot table?
         * > caregiver_user
         * 
         * What are the columns in the pivot table?
         * > caregiver_id, user_id
         */
    }
}
