<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotingLogs extends Model
{
    protected $table = 'voting_logs';
    protected $fillable = ['voting_id', 'candidate_id', 'user_id', 'created_by'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function candidate()
    {
        return $this->belongsTo(VotingCandidates::class, 'candidate_id');
    }

    public function voting()
    {
        return $this->belongsTo(Voting::class, 'voting_id');
    }
}