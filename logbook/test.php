<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $response = \Illuminate\Support\Facades\Http::withToken(config('services.openai.key'))
        ->timeout(60)
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [['role' => 'user', 'content' => 'Test']],
            'max_tokens' => 10
        ]);
    echo 'Status: ' . $response->status() . PHP_EOL;
    echo 'Body: ' . $response->body() . PHP_EOL;
} catch (\Throwable $e) {
    echo 'Exception: ' . $e->getMessage() . PHP_EOL;
}
