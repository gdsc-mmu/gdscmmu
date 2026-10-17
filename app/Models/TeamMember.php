<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_division_id',
        'name',
        'role',
        'email',
        'linkedin',
        'avatar',
        'bio',
        'order',
    ];

    public function division()
    {
        return $this->belongsTo(TeamDivision::class, 'team_division_id');
    }
}
