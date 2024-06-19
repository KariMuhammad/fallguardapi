<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fall extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location',
        'latitude',
        'longitude',
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    // created_at and updated_at are timestamps
    public $timestamps = true;
}
