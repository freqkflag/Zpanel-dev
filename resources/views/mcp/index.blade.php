<x-layout>
    <x-slot:title>
        MCP Servers | Coolify
    </x-slot>
    <div class="flex items-center gap-2">
        <h1>MCP Servers</h1>
        <a href="{{ route('mcp.create') }}">
            <x-forms.button>+ Add Server</x-forms.button>
        </a>
    </div>
    <div class="subtitle">Manage Model Context Protocol (MCP) servers for AI integration.</div>

    @if (session('success'))
        <x-toast type="success" message="{{ session('success') }}" />
    @endif

    <div class="grid gap-4 lg:grid-cols-2 -mt-1">
        @forelse ($servers as $server)
            <div class="box group">
                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <div class="box-title">{{ $server->name }}</div>
                        <div class="box-description">
                            Type: {{ ucfirst($server->type) }}
                            <span class="ml-2">
                                @if ($server->status === 'active')
                                    <span class="text-success">● Active</span>
                                @elseif ($server->status === 'inactive')
                                    <span class="text-warning">● Inactive</span>
                                @else
                                    <span class="text-error">● Error</span>
                                @endif
                            </span>
                        </div>
                        @if ($server->last_health_check)
                            <div class="box-description text-xs">
                                Last health check: {{ $server->last_health_check->diffForHumans() }}
                            </div>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('mcp.edit', $server->id) }}" class="text-sm">Edit</a>
                        <form action="{{ route('mcp.destroy', $server->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Are you sure you want to delete this MCP server?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-error">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2">
                <div>No MCP servers found. <a href="{{ route('mcp.create') }}" class="underline">Create one</a> to get started.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        <a href="{{ route('mcp.config') }}" target="_blank" class="text-sm underline">
            View MCP Configuration JSON
        </a>
    </div>
</x-layout>

