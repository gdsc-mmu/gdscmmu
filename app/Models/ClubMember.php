<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubMember extends Model
{
    use HasFactory;

    protected $table = 'club_members';

    protected $fillable = [
        'name',
        'student_id',
        'term',
        'email',
        'status',
    ];

    /**
     * Scope query to active members.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if membership is expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' || in_array($this->term, ['2430', '2330', '2230']);
    }
}

