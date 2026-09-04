<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedbacks extends Model
{
    protected $fillable = ['sender_name', 'type', 'category_id', 'message', 'created_by'];

    public function category() { return $this->belongsTo(FeedbackCategories::class, 'category_id'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
