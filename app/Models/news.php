<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NewsCategories;
use App\Models\User;

class News extends Model
{
    protected $guarded = [];

    public function category()
{
    return $this->belongsTo(NewsCategories::class, 'category_id');
}

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function updater()
{
    return $this->belongsTo(User::class, 'updated_by');
}
}