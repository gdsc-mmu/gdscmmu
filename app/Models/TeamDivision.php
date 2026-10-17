<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamDivision extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'order',
    ];

    public function members()
    {
        return $this->hasMany(TeamMember::class)->orderBy('order')->orderBy('id');
    }
}
