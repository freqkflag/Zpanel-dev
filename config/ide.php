<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Code Server Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the code-server instance
    |
    */

    'code_server_url' => env('CODE_SERVER_URL', 'http://code-server:8080'),

    'workspace_base' => env('IDE_WORKSPACE_BASE', '/workspace'),

    'token_expiry' => env('IDE_TOKEN_EXPIRY', 24), // hours

    'allowed_extensions' => [
        'php', 'js', 'ts', 'vue', 'css', 'html', 'json',
        'py', 'java', 'go', 'rust', 'cpp', 'c', 'sql',
    ],

    'default_settings' => [
        'editor.fontSize' => 14,
        'editor.fontFamily' => 'Consolas, "Courier New", monospace',
        'editor.wordWrap' => 'on',
    ],
];
