<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccount extends BaseModel
{
    protected $fillable = [
        'firstname',
        'lastname',
        'phoneno',
        'dob',
        'email',
        'password',
        'gender',
        'about',
        'firebase_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'dob' => 'date',
        'gender' => 'string',
    ];
}