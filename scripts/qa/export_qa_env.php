<?php

declare(strict_types=1);

$backendRoot = dirname(__DIR__, 2);
require $backendRoot.'/vendor/autoload.php';

$app = require $backendRoot.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$manifestPath = $backendRoot.'/storage/qa/qa-manifest.json';
if (! file_exists($manifestPath)) {
    fwrite(STDERR, "QA manifest not found. Run: php artisan db:seed --class=QaApiSeeder\n");
    exit(1);
}

/** @var array<string, int|string> $manifest */
$manifest = json_decode(file_get_contents($manifestPath), true, 512, JSON_THROW_ON_ERROR);

if (app()->environment('production')) {
    fwrite(STDERR, "Refusing to export QA Postman environment in production.\n");
    exit(1);
}

$secretKeys = ['admin_password', 'admin_token'];

$values = array_merge([
    'base_url' => 'http://127.0.0.1:8000/api',
    'admin_email' => env('ADMIN_EMAIL', 'admin@limosudcars.local'),
    'admin_password' => env('ADMIN_PASSWORD', 'password'),
    'admin_token' => '',
    'qa_suffix' => (string) time(),
], array_map(static fn ($value): string => (string) $value, $manifest));

$environment = [
    'id' => 'limosud-cars-qa-environment',
    'name' => 'Limosud Cars API QA',
    'values' => array_map(
        static function (string $key, string $value) use ($secretKeys): array {
            return [
                'key' => $key,
                'value' => $value,
                'type' => in_array($key, $secretKeys, true) ? 'secret' : 'default',
                'enabled' => true,
            ];
        },
        array_keys($values),
        array_values($values)
    ),
    '_postman_variable_scope' => 'environment',
    '_postman_exported_at' => gmdate('c'),
    '_postman_exported_using' => 'Limosud QA Workflow',
];

$outputDir = $backendRoot.'/storage/qa/postman';
if (! is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$outputPath = $outputDir.'/LimosudCars_API_QA.postman_environment.json';
file_put_contents($outputPath, json_encode($environment, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

fwrite(STDERR, "Note: generated environment is gitignored and may contain local credentials.\n");
echo "Exported Postman environment to {$outputPath}\n";
