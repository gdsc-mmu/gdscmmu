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

    protected $appends = ['avatar_url'];

    public static function getStaticAvatarMap(): array
    {
        return [
            'issye2' => 'assets/img/gdsc/bigfive/issye2.png',
            'ammar' => 'assets/img/gdsc/bigfive/ammar.png',
            'viinieesh' => 'assets/img/gdsc/bigfive/Viinieesh.png',
            'ariana2' => 'assets/img/gdsc/bigfive/ariana2.png',
            'intan2' => 'assets/img/gdsc/bigfive/intan.png',
            'bukhary' => 'assets/img/gdsc/event/bukhary.png',
            'taseen' => 'assets/img/gdsc/event/taseen.png',
            'elysia' => 'assets/img/gdsc/event/elysia.png',
            'irdy' => 'assets/img/gdsc/event/irdy.png',
            'hanani' => 'assets/img/gdsc/multimedia/hanani.png',
            'liya' => 'assets/img/gdsc/multimedia/liya.png',
            'amirah2' => 'assets/img/gdsc/multimedia/amirah.png',
            'shehab' => 'assets/img/gdsc/multimedia/shehab.png',
            'vidhya' => 'assets/img/gdsc/multimedia/vidhya.png',
            'yuning' => 'assets/img/gdsc/multimedia/yuning.png',
            'nishant' => 'assets/img/gdsc/multimedia/nishant.png',
            'danies' => 'assets/img/gdsc/snp/danies.png',
            'cheammar' => 'assets/img/gdsc/snp/cheammar.png',
            'agilan' => 'assets/img/gdsc/snp/agilan.png',
            'hamizah' => 'assets/img/gdsc/snp/hamizah.png',
            'losha' => 'assets/img/gdsc/snp/losha.png',
            'danish' => 'assets/img/gdsc/cybersec/danish.png',
            'satish' => 'assets/img/gdsc/cybersec/satish.png',
            'prabu' => 'assets/img/gdsc/cybersec/prabu.png',
            'afham' => 'assets/img/gdsc/cybersec/afham.png',
            'viiniesh' => 'assets/img/gdsc/tech/viiniesh.png',
            'raziq' => 'assets/img/gdsc/softwareeng/raziq.png',
            'aleessa' => 'assets/img/gdsc/softwareeng/aleessa.png',
            'liouzi' => 'assets/img/gdsc/softwareeng/liouzi.png',
            'yusof' => 'assets/img/gdsc/softwareeng/yusof.png',
            'rime' => 'assets/img/gdsc/aiml/rime.png',
            'prita' => 'assets/img/gdsc/aiml/prita.png',
            'yong' => 'assets/img/gdsc/aiml/yong.png',
            'raghad' => 'assets/img/gdsc/aiml/raghad.png',
        ];
    }

    /**
     * Get the full URL or Data URI for the member's avatar.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (empty($this->avatar)) {
            return null;
        }

        // Direct external URL or Base64 data URI (stored in Supabase DB)
        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://') || str_starts_with($this->avatar, 'data:')) {
            return $this->avatar;
        }

        // Uploaded file path
        if (str_contains($this->avatar, '/')) {
            return asset($this->avatar);
        }

        // Static CSS avatar key mapping
        $map = self::getStaticAvatarMap();
        if (isset($map[$this->avatar])) {
            return asset($map[$this->avatar]);
        }

        return null;
    }

    public function division()
    {
        return $this->belongsTo(TeamDivision::class, 'team_division_id');
    }
}
