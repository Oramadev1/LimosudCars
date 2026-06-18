<?php

declare(strict_types=1);

$backendRoot = dirname(__DIR__, 2);
$workflowPath = $backendRoot.'/POSTMAN_TEST_WORKFLOW.txt';
$outputDir = $backendRoot.'/storage/qa/postman';
$outputPath = $outputDir.'/LimosudCars_API_QA.postman_collection.json';

$workflow = file_get_contents($workflowPath);
if ($workflow === false) {
    fwrite(STDERR, "Unable to read workflow file.\n");
    exit(1);
}

preg_match_all(
    '/^(\d+)\.\s+(GET|POST|PUT|PATCH|DELETE)\s+(\S+)\s+\[(PUBLIC|ADMIN)\]/m',
    $workflow,
    $matches,
    PREG_OFFSET_CAPTURE
);

$endpoints = [];
$count = count($matches[0]);

for ($i = 0; $i < $count; $i++) {
    $number = (int) $matches[1][$i][0];
    $method = $matches[2][$i][0];
    $path = $matches[3][$i][0];
    $auth = $matches[4][$i][0];
    $start = $matches[0][$i][1];
    $end = ($i + 1 < $count) ? $matches[0][$i + 1][1] : strlen($workflow);
    $section = substr($workflow, $start, $end - $start);

    $body = null;
    $multipart = false;

    if (preg_match('/Request Body:\s*```json\s*(.*?)\s*```/s', $section, $bodyMatch)) {
        $body = trim($bodyMatch[1]);
    } elseif (preg_match('/Request Body:\s*multipart\/form-data:/s', $section)) {
        $multipart = true;
    }

    $endpoints[] = compact('number', 'method', 'path', 'auth', 'body', 'multipart', 'section');
}

$urlOverrides = require __DIR__.'/postman_url_overrides.php';
$bodyOverrides = require __DIR__.'/postman_body_overrides.php';
$multipartOverrides = require __DIR__.'/postman_multipart_overrides.php';
$testScripts = require __DIR__.'/postman_test_scripts.php';
$queryOverrides = require __DIR__.'/postman_query_overrides.php';
$jsonBodyInsteadOfMultipart = [83];

$items = [];

foreach ($endpoints as $endpoint) {
    $number = $endpoint['number'];
    $method = $endpoint['method'];
    $rawPath = $endpoint['path'];
    $auth = $endpoint['auth'];

    $urlPath = $urlOverrides[$number] ?? str_replace(
        ['{vehicle}', '{slug}', '{brand}', '{category}', '{customer}', '{document}', '{location}', '{reservation}', '{payment}', '{contract}', '{maintenance}', '{expense}', '{alert}'],
        ['{{vehicle_id}}', '{{public_vehicle_slug}}', '{{vehicle_brand_id}}', '{{vehicle_category_id}}', '{{customer_id}}', '{{customer_document_id}}', '{{location_id}}', '{{reservation_admin_id}}', '{{payment_id}}', '{{contract_id}}', '{{maintenance_id}}', '{{expense_id}}', '{{alert_id}}'],
        $rawPath
    );

    $urlPath = ltrim(str_replace('/api', '', $urlPath), '/');
    $urlSegments = array_values(array_filter(explode('/', $urlPath), static fn (string $segment): bool => $segment !== ''));

    $query = $queryOverrides[$number] ?? [];
    $queryString = $query === [] ? '' : '?'.http_build_query($query);

    $request = [
        'method' => $method,
        'header' => [
            ['key' => 'Accept', 'value' => $number === 72 ? 'application/pdf' : 'application/json', 'type' => 'text'],
        ],
        'url' => [
            'raw' => '{{base_url}}/'.$urlPath.$queryString,
            'host' => ['{{base_url}}'],
            'path' => $urlSegments,
            'query' => array_map(
                static fn (string $key, string $value): array => ['key' => $key, 'value' => $value],
                array_keys($query),
                array_values($query)
            ),
        ],
    ];

    if ($auth === 'ADMIN' && $number !== 9) {
        $request['header'][] = ['key' => 'Authorization', 'value' => 'Bearer {{admin_token}}', 'type' => 'text'];
    }

    if ($endpoint['multipart'] && ! in_array($number, $jsonBodyInsteadOfMultipart, true)) {
        $formData = $multipartOverrides[$number] ?? [];
        $request['body'] = [
            'mode' => 'formdata',
            'formdata' => $formData,
        ];
    } elseif (isset($bodyOverrides[$number]) || $endpoint['body'] !== null) {
        $body = $bodyOverrides[$number] ?? $endpoint['body'];
        $request['header'][] = ['key' => 'Content-Type', 'value' => 'application/json', 'type' => 'text'];
        $request['body'] = [
            'mode' => 'raw',
            'raw' => $body,
        ];
    }

    $name = sprintf('%02d - %s %s [%s]', $number, $method, $rawPath, $auth);
    $event = [];

    if ($number === 9) {
        $event[] = [
            'listen' => 'test',
            'script' => [
                'type' => 'text/javascript',
                'exec' => [
                    "pm.test('Login returns token', function () {",
                    '    pm.response.to.have.status(200);',
                    "    const json = pm.response.json();",
                    "    pm.expect(json).to.have.property('access_token');",
                    "    pm.environment.set('admin_token', json.access_token);",
                    '});',
                ],
            ],
        ];
    } else {
        $event[] = [
            'listen' => 'test',
            'script' => [
                'type' => 'text/javascript',
                'exec' => array_merge(
                    [
                        "pm.test('Status code is successful', function () {",
                        '    pm.expect(pm.response.code).to.be.oneOf([200, 201, 204]);',
                        '});',
                    ],
                    $testScripts[$number] ?? []
                ),
            ],
        ];
    }

    $items[] = [
        'name' => $name,
        'request' => $request,
        'event' => $event,
        '_endpoint_number' => $number,
    ];
}

$logoutItem = null;
$orderedItems = [];

foreach ($items as $item) {
    if (($item['_endpoint_number'] ?? null) === 11) {
        $logoutItem = $item;
        continue;
    }

    unset($item['_endpoint_number']);
    $orderedItems[] = $item;
}

if ($logoutItem !== null) {
    unset($logoutItem['_endpoint_number']);
    $logoutItem['name'] = '97 - POST /api/admin/auth/logout [ADMIN] (run last)';
    $orderedItems[] = $logoutItem;
}

$collection = [
    'info' => [
        '_postman_id' => 'limosud-cars-qa-collection',
        'name' => 'Limosud Cars API QA (96 Endpoints)',
        'description' => 'Auto-generated QA collection for Newman API testing.',
        'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
    ],
    'event' => [
        [
            'listen' => 'prerequest',
            'script' => [
                'type' => 'text/javascript',
                'exec' => [
                    "if (!pm.environment.get('qa_suffix')) {",
                    "    pm.environment.set('qa_suffix', String(Date.now()));",
                    '}',
                ],
            ],
        ],
    ],
    'item' => $orderedItems,
];

if (! is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

file_put_contents($outputPath, json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "Generated {$outputPath} with ".count($orderedItems)." requests.\n";
