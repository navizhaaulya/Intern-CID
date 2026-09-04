<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotingLogs extends Model
{
    protected $fillable = ['voting_id', 'candidate_id', 'user_id'];

    public function voting() { return $this->belongsTo(Voting::class); }
    public function candidate() { return $this->belongsTo(VotingCandidates::class, 'candidate_id'); }
    public function user() { return $this->belongsTo(User::class); }
}
