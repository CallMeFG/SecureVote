<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = ['voting_period_id', 'nim', 'name', 'vice_nim', 'vice_name', 'vision', 'mission', 'photo_url'];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function votingPeriod()
    {
        return $this->belongsTo(VotingPeriod::class);
    }
}
