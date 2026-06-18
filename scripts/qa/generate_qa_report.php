<?php

declare(strict_types=1);

$backendRoot = dirname(__DIR__, 2);
$newmanReportPath = $backendRoot.'/storage/qa/reports/newman-results.json';
$capturedPath = $backendRoot.'/storage/qa/reports/captured-responses.json';
$outputPath = $backendRoot.'/storage/qa/reports/QA_API_TEST_REPORT.txt';

if (! file_exists($newmanReportPath)) {
    fwrite(STDERR, "Newman report not found.\n");
    exit(1);
}

/** @var array<string, mixed> $report */
$report = json_decode(file_get_contents($newmanReportPath), true, 512, JSON_THROW_ON_ERROR);
$captured = file_exists($capturedPath)
    ? json_decode(file_get_contents($capturedPath), true, 512, JSON_THROW_ON_ERROR)
    : [];

$stats = $report['run']['stats'] ?? [];
$executions = $report['run']['executions'] ?? [];

$totalEndpoints = count($executions);
$passed = 0;
$failed = 0;
$validationErrors = [];
$docMismatches = [];
$failures = [];

foreach ($executions as $execution) {
    $name = $execution['item']['name'] ?? 'Unknown';
    $response = $execution['response'] ?? [];
    $status = (int) ($response['code'] ?? 0);
    $assertions = $execution['assertions'] ?? [];
    $requestFailed = false;

    foreach ($assertions as $assertion) {
        if (($assertion['error'] ?? null) !== null) {
            $requestFailed = true;
            $failures[] = [
                'name' => $name,
                'assertion' => $assertion['assertion'] ?? 'Assertion',
                'error' => $assertion['error']['message'] ?? 'Unknown assertion error',
            ];
        }
    }

    if ($status === 422) {
        $body = $response['stream'] ?? '';
        $validationErrors[] = [
            'name' => $name,
            'status' => $status,
            'body' => is_string($body) ? json_decode($body, true) : $body,
        ];
        $requestFailed = true;
    } elseif (! in_array($status, [200, 201, 204], true)) {
        $requestFailed = true;
        $failures[] = [
            'name' => $name,
            'assertion' => 'HTTP status',
            'error' => "Unexpected status {$status}",
        ];
    }

    if ($requestFailed) {
        $failed++;
    } else {
        $passed++;
    }

    if (preg_match('/^97\s+-.*logout/i', $name)) {
        $endpointNumber = 11;
    } elseif (preg_match('/^(\d+)\s+-/', $name, $match)) {
        $endpointNumber = (int) $match[1];
    } else {
        $endpointNumber = null;
    }

    if ($endpointNumber !== null) {
        $isBinarySuccess = $endpointNumber === 72 && $status === 200;
        $isNoContentSuccess = $status === 204;

        $capturedKey = (string) $endpointNumber;
        if (! isset($captured[$capturedKey]) && ! $isBinarySuccess && ! $isNoContentSuccess && in_array($status, [200, 201], true)) {
            $docMismatches[] = "Endpoint {$endpointNumber}: successful response executed but was not captured into workflow documentation.";
        }
    }
}

$lines = [
    'LIMOSUD CARS - QA API TEST REPORT',
    str_repeat('=', 64),
    'Generated at: '.date('Y-m-d H:i:s'),
    '',
    'SUMMARY',
    str_repeat('-', 64),
    'Total endpoints tested: '.$totalEndpoints,
    'Successful tests: '.$passed,
    'Failed tests: '.$failed,
    'Validation errors (HTTP 422): '.count($validationErrors),
    'Documentation capture gaps: '.count($docMismatches),
    '',
    'NEWMAN STATS',
    str_repeat('-', 64),
    'Requests: '.($stats['requests']['total'] ?? $totalEndpoints),
    'Assertions total: '.($stats['assertions']['total'] ?? 0),
    'Assertions failed: '.($stats['assertions']['failed'] ?? 0),
    '',
];

if ($failures !== []) {
    $lines[] = 'FAILED TESTS';
    $lines[] = str_repeat('-', 64);
    foreach ($failures as $failure) {
        $lines[] = '- '.$failure['name'];
        $lines[] = '  '.$failure['assertion'].': '.$failure['error'];
    }
    $lines[] = '';
}

if ($validationErrors !== []) {
    $lines[] = 'VALIDATION ERRORS';
    $lines[] = str_repeat('-', 64);
    foreach ($validationErrors as $error) {
        $lines[] = '- '.$error['name'].' (HTTP '.$error['status'].')';
        $lines[] = '  '.json_encode($error['body'], JSON_UNESCAPED_SLASHES);
    }
    $lines[] = '';
}

if ($docMismatches !== []) {
    $lines[] = 'DOCUMENTATION VS EXECUTION DIFFERENCES';
    $lines[] = str_repeat('-', 64);
    foreach ($docMismatches as $mismatch) {
        $lines[] = '- '.$mismatch;
    }
    $lines[] = '';
} else {
    $lines[] = 'DOCUMENTATION VS EXECUTION DIFFERENCES';
    $lines[] = str_repeat('-', 64);
    $lines[] = 'No mismatches detected. JSON responses were captured into POSTMAN_TEST_WORKFLOW.txt.';
    $lines[] = 'Binary PDF (endpoint 72) and HTTP 204 responses are documented without JSON payloads.';
    $lines[] = '';
}

$lines[] = 'ARTIFACTS';
$lines[] = str_repeat('-', 64);
$lines[] = 'Postman collection: storage/qa/postman/LimosudCars_API_QA.postman_collection.json';
$lines[] = 'Postman environment: storage/qa/postman/LimosudCars_API_QA.postman_environment.json';
$lines[] = 'Newman JSON report: storage/qa/reports/newman-results.json (gitignored)';
$lines[] = 'Captured responses: storage/qa/reports/captured-responses.json (gitignored)';
$lines[] = 'Updated workflow doc: POSTMAN_TEST_WORKFLOW.txt (tokens redacted)';
$lines[] = '';
$lines[] = 'SECURITY';
$lines[] = str_repeat('-', 64);
$lines[] = 'This report contains summary counts only. Raw Newman JSON and captured responses stay in storage/qa/reports/ and are gitignored.';
$lines[] = 'Login access_token values are redacted before POSTMAN_TEST_WORKFLOW.txt is updated.';

if (! is_dir(dirname($outputPath))) {
    mkdir(dirname($outputPath), 0777, true);
}

file_put_contents($outputPath, implode(PHP_EOL, $lines).PHP_EOL);
echo "Generated report at {$outputPath}\n";
