<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'end_date',
        'image',
        'user_id',
        'bevy_id',
        'bevy_url',
        'location',
        'event_type',
        'tags',
    ];

    protected $casts = [
        'date' => 'date',
        'end_date' => 'datetime',
        'tags' => 'array',
    ];

    /**
     * Get the banner image URL (handles both external Cloudinary URLs and local uploads).
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return 'https://images.unsplash.com/photo-1520004434532-668416a08753?q=80&w=2070&auto=format&fit=crop';
        }
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        return asset('images/' . $this->image);
    }

    /**
     * Check if this event was synced from Bevy.
     */
    public function isFromBevy(): bool
    {
        return !empty($this->bevy_id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
