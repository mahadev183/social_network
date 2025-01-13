<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends BaseModel
{
    protected $fillable = [
        'user_id',
        'file',
        'posted_date',
        'comment_enable',
    ];

    protected $casts = [
        'posted_date' => 'datetime',
        'comment_enable' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }
}
