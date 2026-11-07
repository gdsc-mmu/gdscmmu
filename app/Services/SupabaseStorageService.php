<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseStorageService
{
    protected string $projectUrl;
    protected string $key;
    protected string $bucket;

    public function __construct()
    {
        $this->projectUrl = rtrim(env('SUPABASE_URL', 'https://gsmmhesrrvwxrpzholxc.supabase.co'), '/');
        $this->key = env('SUPABASE_SERVICE_ROLE_KEY', env('SUPABASE_KEY', 'sb_publishable_pWTRNjDTbLxAU-GYZzRZtw_Z1051JcE'));
        $this->bucket = env('SUPABASE_STORAGE_BUCKET', 'team_avatars');
    }

    /**
     * Upload an avatar file to Supabase Storage.
     * Falls back to Base64 Data URI if RLS or network error occurs.
     */
    public function uploadAvatar(UploadedFile $file): string
    {
        $ext = $file->getClientOriginalExtension() ?: 'png';
        $filename = 'avatar_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $mime = $file->getMimeType() ?: 'image/png';
        $contents = file_get_contents($file->getRealPath());

        try {
            $uploadUrl = "{$this->projectUrl}/storage/v1/object/{$this->bucket}/{$filename}";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->key}",
                'apikey' => $this->key,
                'Content-Type' => $mime,
            ])->timeout(10)->withBody($contents, $mime)->post($uploadUrl);

            if ($response->successful()) {
                $publicUrl = "{$this->projectUrl}/storage/v1/object/public/{$this->bucket}/{$filename}";
                Log::info("SupabaseStorageService: Uploaded avatar successfully to {$publicUrl}");
                return $publicUrl;
            }

            Log::warning("SupabaseStorageService: Bucket upload returned {$response->status()}: {$response->body()}. Falling back to database Base64.");
        } catch (\Exception $e) {
            Log::warning("SupabaseStorageService: Exception during bucket upload: " . $e->getMessage() . ". Falling back to database Base64.");
        }

        // Fallback: Store as Base64 Data URI directly in Supabase PostgreSQL
        return 'data:' . $mime . ';base64,' . base64_encode($contents);
    }
}
