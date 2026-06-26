<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    protected $fillable = [
        'content',
        'order',
        'status_code',
        'created_by',
        'updated_by'
    ];
    
}