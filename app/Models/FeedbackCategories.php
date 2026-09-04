<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackCategories extends Model
{
    protected $table = 'feedback_categories';
    protected $fillable = ['name']; // sesuaikan kalau kolomnya beda

    public function feedbacks() { return $this->hasMany(Feedbacks::class, 'category_id'); }
}