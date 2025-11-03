<x-layout>
    <x-slot:title>
        Create MCP Server | Coolify
    </x-slot>
    <h1>Create MCP Server</h1>
    <div class="subtitle">Register a new Model Context Protocol server.</div>

    <form action="{{ route('mcp.store') }}" method="POST" class="max-w-2xl">
        @csrf

        <x-forms.input id="name" label="Server Name" required helper="Unique name for this MCP server" />
        @error('name')
            <div class="text-error text-sm mt-1">{{ $message }}</div>
        @enderror

        <x-forms.select id="type" label="Server Type" required helper="Select the type of MCP server">
            @foreach ($serverTypes as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </x-forms.select>
        @error('type')
            <div class="text-error text-sm mt-1">{{ $message }}</div>
        @enderror

        <div class="mt-4">
            <label class="flex gap-1 items-center mb-1 text-sm font-medium">
                Configuration (JSON)
                <x-highlighted text="*" />
            </label>
            <x-forms.textarea id="config" rows="10" required
                helper="JSON configuration for the MCP server. Example: {&quot;command&quot;: &quot;php&quot;, &quot;args&quot;: [&quot;artisan&quot;, &quot;boost:mcp&quot;]}" />
            <small class="text-xs text-coolgray-400">Enter valid JSON configuration</small>
            @error('config')
                <div class="text-error text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex gap-2 mt-4">
            <x-forms.button type="submit">Create Server</x-forms.button>
            <a href="{{ route('mcp.index') }}" class="button">Cancel</a>
        </div>
    </form>
</x-layout>

<script>
    // Parse JSON configuration on form submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const configTextarea = document.getElementById('config');
        const configValue = configTextarea.value.trim();

        if (configValue) {
            try {
                const parsed = JSON.parse(configValue);
                // Create hidden input with parsed JSON
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'config';
                hiddenInput.value = JSON.stringify(parsed);
                this.appendChild(hiddenInput);
                configTextarea.name = '';
            } catch (error) {
                e.preventDefault();
                alert('Invalid JSON configuration: ' + error.message);
            }
        }
    });
</script>

