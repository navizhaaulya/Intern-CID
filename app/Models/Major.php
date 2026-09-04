<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable = [
        'slug', 'img_logo', 'code', 'major_name', 'summary', 'total_classes',
        'major_duration', 'full_description', 'status_code', 'created_by', 'updated_by',
    ];

    public function competents() { return $this->hasMany(MajorCompetent::class); }
    public function galleries() { return $this->hasMany(MajorGallery::class); }
}