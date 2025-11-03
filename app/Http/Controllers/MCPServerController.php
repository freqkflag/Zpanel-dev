<?php

namespace App\Http\Controllers;

use App\Models\MCPServer;
use App\Services\MCP\ServerRegistry;
use Illuminate\Http\Request;

class MCPServerController extends Controller
{
    public function __construct(
        private ServerRegistry $registry
    ) {
        $this->middleware('auth');
    }

    /**
     * List all MCP servers
     */
    public function index()
    {
        $servers = MCPServer::all();

        return view('mcp.index', [
            'servers' => $servers,
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('mcp.create', [
            'serverTypes' => MCPServer::getAvailableTypes(),
        ]);
    }

    /**
     * Store new MCP server
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:mcp_servers,name',
            'type' => 'required|string|in:cloudflare,github,database,docker,custom',
            'config' => 'required',
        ]);

        // Handle JSON string input
        if (is_string($validated['config'])) {
            $validated['config'] = json_decode($validated['config'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['config' => 'Invalid JSON configuration'])->withInput();
            }
        }

        $validated['config'] = array_merge($validated['config'], ['type' => $validated['type']]);

        $server = $this->registry->register(
            $validated['name'],
            $validated['config']
        );

        return redirect()->route('mcp.index')
            ->with('success', 'MCP Server created successfully');
    }

    /**
     * Show edit form
     */
    public function edit(MCPServer $mcpServer)
    {
        return view('mcp.edit', [
            'server' => $mcpServer,
            'serverTypes' => MCPServer::getAvailableTypes(),
        ]);
    }

    /**
     * Update MCP server
     */
    public function update(Request $request, MCPServer $mcpServer)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:mcp_servers,name,'.$mcpServer->id,
            'type' => 'required|string',
            'config' => 'required',
            'status' => 'required|string|in:active,inactive,error',
        ]);

        // Handle JSON string input
        if (is_string($validated['config'])) {
            $validated['config'] = json_decode($validated['config'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['config' => 'Invalid JSON configuration'])->withInput();
            }
        }

        $mcpServer->update($validated);

        return redirect()->route('mcp.index')
            ->with('success', 'MCP Server updated successfully');
    }

    /**
     * Delete MCP server
     */
    public function destroy(MCPServer $mcpServer)
    {
        $mcpServer->delete();

        return redirect()->route('mcp.index')
            ->with('success', 'MCP Server deleted successfully');
    }

    /**
     * Health check
     */
    public function healthCheck(MCPServer $mcpServer)
    {
        $health = $this->registry->healthCheck($mcpServer->name);

        return response()->json($health);
    }

    /**
     * Get MCP configuration JSON
     */
    public function config()
    {
        $config = $this->registry->generateConfig();

        return response()->json($config);
    }
}
