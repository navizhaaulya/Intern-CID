<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\News;
use App\Models\User;

class NewsCategory extends Model
{
    protected $table = 'news_categories';

    protected $guarded = [];

    public function news()
    {
        return $this->hasMany(News::class, 'category_id');
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