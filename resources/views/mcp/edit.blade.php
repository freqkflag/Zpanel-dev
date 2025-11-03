<x-layout>
    <x-slot:title>
        Edit MCP Server | Coolify
    </x-slot>
    <h1>Edit MCP Server</h1>
    <div class="subtitle">Update MCP server configuration.</div>

    <form action="{{ route('mcp.update', $server->id) }}" method="POST" class="max-w-2xl">
        @csrf
        @method('PUT')

        <x-forms.input id="name" label="Server Name" value="{{ old('name', $server->name) }}" required />
        @error('name')
            <div class="text-error text-sm mt-1">{{ $message }}</div>
        @enderror

        <x-forms.select id="type" label="Server Type" required>
            @foreach ($serverTypes as $value => $label)
                <option value="{{ $value }}" {{ old('type', $server->type) === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </x-forms.select>
        @error('type')
            <div class="text-error text-sm mt-1">{{ $message }}</div>
        @enderror

        <x-forms.select id="status" label="Status" required>
            <option value="active" {{ old('status', $server->status) === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $server->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="error" {{ old('status', $server->status) === 'error' ? 'selected' : '' }}>Error</option>
        </x-forms.select>
        @error('status')
            <div class="text-error text-sm mt-1">{{ $message }}</div>
        @enderror

        <div class="mt-4">
            <label class="flex gap-1 items-center mb-1 text-sm font-medium">
                Configuration (JSON)
                <x-highlighted text="*" />
            </label>
            <x-forms.textarea id="config" rows="10" required>{{ json_encode(old('config', $server->config), JSON_PRETTY_PRINT) }}</x-forms.textarea>
            <small class="text-xs text-coolgray-400">Enter valid JSON configuration</small>
            @error('config')
                <div class="text-error text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex gap-2 mt-4">
            <x-forms.button type="submit">Update Server</x-forms.button>
            <a href="{{ route('mcp.index') }}" class="button">Cancel</a>
            <a href="{{ route('mcp.health', $server->id) }}" class="button" target="_blank">Health Check</a>
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

