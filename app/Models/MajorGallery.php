<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MajorGallery extends Model
{
    protected $table = 'major_gallery'; 

    protected $fillable = ['major_id', 'img_cover', 'description', 'created_by', 'updated_by'];

    public function major() { return $this->belongsTo(Major::class); }
}