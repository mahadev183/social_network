<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $fillable = [
        'created_by',
        'modified_by',
        'created_at',
        'modified_at',
        'active',
    ];

    public $timestamps = true;

    public function createdByUser()
    {
        return $this->belongsTo(UserAccount::class, 'created_by');
    }

    public function modifiedByUser()
    {
        return $this->belongsTo(UserAccount::class, 'modified_by');
    }
}