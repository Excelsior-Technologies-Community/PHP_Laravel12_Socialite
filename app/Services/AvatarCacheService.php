<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AvatarCacheService
{
    /**
     * Download remote social avatar image and cache it in public/storage/avatars/
     */
    public function cacheAvatar(User $user, ?string $remoteUrl, string $provider = 'google'): ?string
    {
        if (empty($remoteUrl)) {
            return null;
        }

        try {
            $avatarDirectory = public_path('storage/avatars');
            if (!File::exists($avatarDirectory)) {
                File::makeDirectory($avatarDirectory, 0755, true, true);
            }

            $fileName = "avatar_{$user->id}_{$provider}.jpg";
            $localFilePath = $avatarDirectory . '/' . $fileName;

            // Fetch remote avatar image
            $response = Http::timeout(10)->get($remoteUrl);

            if ($response->successful()) {
                File::put($localFilePath, $response->body());
                $localUrl = "/storage/avatars/{$fileName}";

                $user->local_avatar = $localUrl;
                $user->save();

                return $localUrl;
            }
        } catch (\Throwable $e) {
            Log::warning("Failed to cache avatar locally for user {$user->id}", [
                'provider' => $provider,
                'url' => $remoteUrl,
                'error' => $e->getMessage(),
            ]);
        }

        return $remoteUrl;
    }
}
