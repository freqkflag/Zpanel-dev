<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MCPServer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'config',
        'status',
        'last_error',
        'last_health_check',
    ];

    protected $casts = [
        'config' => 'array',
        'last_health_check' => 'datetime',
    ];

    /**
     * Server types
     */
    public const TYPE_CLOUDFLARE = 'cloudflare';

    public const TYPE_GITHUB = 'github';

    public const TYPE_DATABASE = 'database';

    public const TYPE_DOCKER = 'docker';

    public const TYPE_CUSTOM = 'custom';

    /**
     * Server statuses
     */
    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_ERROR = 'error';

    /**
     * Get available server types
     */
    public static function getAvailableTypes(): array
    {
        return [
            self::TYPE_CLOUDFLARE => 'Cloudflare',
            self::TYPE_GITHUB => 'GitHub',
            self::TYPE_DATABASE => 'Database',
            self::TYPE_DOCKER => 'Docker',
            self::TYPE_CUSTOM => 'Custom',
        ];
    }

    /**
     * Check if server is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
