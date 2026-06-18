<?php

declare(strict_types=1);

/**
 * Scan committable documentation/QA files for accidental secrets.
 * Run from backend/: php scripts/qa/audit_committable_secrets.php
 */

$backendRoot = dirname(__DIR__, 2);

$scanRoots = [
    $backendRoot.'/POSTMAN_TEST_WORKFLOW.txt',
    $backendRoot.'/FRONTEND_API_INTEGRATION_GUIDE.md',
    $backendRoot.'/FRONTEND_WORKFLOWS.md',
    $backendRoot.'/FRONTEND_API_WORKFLOW.txt',
    $backendRoot.'/FRONTEND_ONBOARDING.md',
    $backendRoot.'/scripts/qa',
    $backendRoot.'/database/seeders/QaApiSeeder.php',
    $backendRoot.'/database/seeders/QaDatabaseSeeder.php',
    $backendRoot.'/storage/qa/postman/LimosudCars_API_QA.postman_collection.json',
    $backendRoot.'/storage/qa/postman/LimosudCars_API_QA.postman_environment.example.json',
];

$patterns = [
    'sanctum_token' => '/\d+\|[A-Za-z0-9]{16,}/',
    'access_token_value' => '/"access_token"\s*:\s*"(?!&lt;REDACTED)[^"]{20,}"/i',
    'bearer_literal' => '/Bearer\s+[A-Za-z0-9\|._-]{20,}/',
    'password_literal' => '/"password"\s*:\s*"(?!(\{\{|\*\*\*|\&lt;))[^"]+"/i',
    'api_key_literal' => '/"api_key"\s*:\s*"[^"]+"/i',
    'secret_literal' => '/"secret"\s*:\s*"[^"]+"/i',
];

$findings = [];

foreach ($scanRoots as $root) {
    if (! file_exists($root)) {
        continue;
    }

    $files = is_dir($root)
        ? new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root))
        : [$root];

    foreach ($files as $file) {
        if ($file instanceof SplFileInfo && $file->isDir()) {
            continue;
        }

        $path = $file instanceof SplFileInfo ? $file->getPathname() : $file;
        if (! is_file($path)) {
            continue;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($ext, ['php', 'md', 'txt', 'json', 'ps1'], true)) {
            continue;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            continue;
        }

        foreach ($lines as $num => $line) {
            foreach ($patterns as $label => $regex) {
                if (preg_match($regex, $line, $m)) {
                    $findings[] = [
                        'file' => str_replace($backendRoot.'\\', '', str_replace($backendRoot.'/', '', $path)),
                        'line' => $num + 1,
                        'type' => $label,
                        'snippet' => mb_substr(trim($line), 0, 120),
                    ];
                }
            }
        }
    }
}

$reportPath = $backendRoot.'/storage/qa/reports/COMMITTABLE_SECRETS_AUDIT.txt';
@mkdir(dirname($reportPath), 0777, true);

$out = [];
$out[] = 'Limosud Cars — Committable files secrets audit';
$out[] = 'Generated: '.date('Y-m-d H:i:s');
$out[] = '';
$out[] = 'Scanned paths:';
foreach ($scanRoots as $r) {
    $out[] = '  - '.str_replace($backendRoot, '.', $r);
}
$out[] = '';
$out[] = 'Findings: '.count($findings);
$out[] = str_repeat('-', 60);

if ($findings === []) {
    $out[] = 'No suspicious secret patterns detected.';
} else {
    foreach ($findings as $f) {
        $out[] = sprintf('[%s] %s:%d', $f['type'], $f['file'], $f['line']);
        $out[] = '  '.$f['snippet'];
    }
}

$text = implode(PHP_EOL, $out).PHP_EOL;
file_put_contents($reportPath, $text);

echo $text;
exit($findings === [] ? 0 : 1);
