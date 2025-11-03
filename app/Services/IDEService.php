<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class IDEService
{
    private string $codeServerUrl;

    public function __construct()
    {
        $this->codeServerUrl = config('ide.code_server_url', 'http://code-server:8080');
    }

    /**
     * Generate secure token for IDE access
     */
    public function generateToken(int $userId): string
    {
        $token = Str::random(32);
        Cache::put("ide_token_{$token}", [
            'user_id' => $userId,
            'created_at' => now(),
            'expires_at' => now()->addHours(24),
        ], now()->addHours(24));

        return $token;
    }

    /**
     * Validate IDE token
     */
    public function validateToken(string $token): ?array
    {
        return Cache::get("ide_token_{$token}");
    }

    /**
     * Get workspace path for user
     */
    public function getWorkspacePath(int $userId, ?string $projectId = null): string
    {
        $basePath = config('ide.workspace_base', '/workspace');

        if ($projectId) {
            return "{$basePath}/user_{$userId}/project_{$projectId}";
        }

        return "{$basePath}/user_{$userId}";
    }

    /**
     * Get IDE URL with authentication
     */
    public function getIDEUrl(string $token, string $workspace): string
    {
        return "{$this->codeServerUrl}/?folder={$workspace}&tkn={$token}";
    }
}
