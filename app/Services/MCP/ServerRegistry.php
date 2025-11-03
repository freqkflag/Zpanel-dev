<?php

namespace App\Services\MCP;

use App\Models\MCPServer;

class ServerRegistry
{
    /**
     * Register a new MCP server
     */
    public function register(string $name, array $config): MCPServer
    {
        return MCPServer::create([
            'name' => $name,
            'type' => $config['type'] ?? 'custom',
            'config' => $config,
            'status' => 'active',
        ]);
    }

    /**
     * Get MCP server by name
     */
    public function getServer(string $name): ?MCPServer
    {
        return MCPServer::where('name', $name)
            ->where('status', 'active')
            ->first();
    }

    /**
     * List all active MCP servers
     */
    public function listServers(): array
    {
        return MCPServer::where('status', 'active')
            ->get()
            ->map(function ($server) {
                return [
                    'name' => $server->name,
                    'type' => $server->type,
                    'status' => $server->status,
                    'config' => $server->config,
                ];
            })
            ->toArray();
    }

    /**
     * Update server status
     */
    public function updateStatus(string $name, string $status): bool
    {
        $server = $this->getServer($name);
        if (! $server) {
            return false;
        }

        $server->update(['status' => $status]);

        return true;
    }

    /**
     * Health check for MCP server
     */
    public function healthCheck(string $name): array
    {
        $server = $this->getServer($name);
        if (! $server) {
            return ['status' => 'not_found'];
        }

        // TODO: Implement actual health check based on server type
        $server->update(['last_health_check' => now()]);

        return [
            'status' => 'healthy',
            'name' => $server->name,
            'checked_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Generate .mcp.json configuration
     */
    public function generateConfig(): array
    {
        $servers = MCPServer::where('status', 'active')->get();

        $mcpConfig = ['mcpServers' => []];

        foreach ($servers as $server) {
            $mcpConfig['mcpServers'][$server->name] = $server->config;
        }

        // Always include Laravel Boost MCP server
        $mcpConfig['mcpServers']['laravel-boost'] = [
            'command' => 'php',
            'args' => ['artisan', 'boost:mcp'],
        ];

        return $mcpConfig;
    }
}
