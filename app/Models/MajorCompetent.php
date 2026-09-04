<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MajorCompetent extends Model
{
    protected $table = 'major_competent'; 

    protected $fillable = ['major_id', 'competent_name', 'description', 'created_by', 'updated_by'];

    public function major() { return $this->belongsTo(Major::class); }
}