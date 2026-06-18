<?php

declare(strict_types=1);

$backendRoot = dirname(__DIR__, 2);
require __DIR__.'/sanitize_response.php';

$workflowPath = $backendRoot.'/POSTMAN_TEST_WORKFLOW.txt';
$newmanReportPath = $backendRoot.'/storage/qa/reports/newman-results.json';
$capturedPath = $backendRoot.'/storage/qa/reports/captured-responses.json';

if (! file_exists($newmanReportPath)) {
    fwrite(STDERR, "Newman report not found at {$newmanReportPath}\n");
    exit(1);
}

/** @var array<string, mixed> $report */
$report = json_decode(file_get_contents($newmanReportPath), true, 512, JSON_THROW_ON_ERROR);
$workflow = file_get_contents($workflowPath);
if ($workflow === false) {
    fwrite(STDERR, "Unable to read workflow file.\n");
    exit(1);
}

$workflow = str_replace("\r\n", "\n", $workflow);

$streamToString = static function (mixed $stream): string {
    if (is_string($stream)) {
        return $stream;
    }

    if (! is_array($stream)) {
        return '';
    }

    if (($stream['type'] ?? null) === 'Buffer' && is_array($stream['data'] ?? null)) {
        return implode('', array_map(static fn (int $byte): string => chr($byte), $stream['data']));
    }

    return '';
};

$captured = [];
$replacements = [];

foreach ($report['run']['executions'] as $execution) {
    $name = $execution['item']['name'] ?? '';
    if (preg_match('/^97\s+-.*logout/i', $name)) {
        $number = 11;
    } elseif (! preg_match('/^(\d+)\s+-/', $name, $match)) {
        continue;
    } else {
        $number = (int) $match[1];
    }

    $response = $execution['response'] ?? null;
    if ($response === null) {
        continue;
    }

    $status = (int) ($response['code'] ?? 0);
    $body = $streamToString($response['stream'] ?? '');
    $contentType = $response['header'] ?? [];

    $isJson = $body !== '' && str_starts_with(trim($body), '{');
    $isPdf = false;

    foreach ($contentType as $header) {
        if (($header['key'] ?? '') === 'Content-Type' && str_contains((string) ($header['value'] ?? ''), 'pdf')) {
            $isPdf = true;
        }
    }

    if ($status === 204) {
        $replacement = "Success Response:\n✅ Captured from Newman run on ".date('Y-m-d').".\nHTTP 204 No Content. Empty response body.";
    } elseif ($isPdf) {
        $replacement = "Success Response:\n✅ Captured from Newman run on ".date('Y-m-d').".\nBinary PDF download. No JSON body.";
    } elseif ($isJson) {
        $decoded = json_decode($body, true);
        $sanitized = sanitize_qa_response_payload($decoded, $number);
        $pretty = json_encode($sanitized, JSON_UNESCAPED_SLASHES);
        $replacement = "Success Response:\n✅ Captured from Newman run on ".date('Y-m-d').".\n```json\n{$pretty}\n```";
        $captured[$number] = [
            'status' => $status,
            'body' => $sanitized,
        ];
    } else {
        $sanitizedBody = sanitize_qa_response_string(trim($body));
        $replacement = "Success Response:\n✅ Captured from Newman run on ".date('Y-m-d').".\n```\n{$sanitizedBody}\n```";
        $captured[$number] = [
            'status' => $status,
            'body' => $sanitizedBody,
        ];
    }

    $replacements[$number] = rtrim($replacement);
}

$sections = preg_split('/\n(?=\d+\. (?:GET|POST|PUT|PATCH|DELETE) )/m', $workflow);
if ($sections === false) {
    fwrite(STDERR, "Unable to split workflow sections.\n");
    exit(1);
}

$updatedSections = [];
$replacedCount = 0;

foreach ($sections as $section) {
    if (! preg_match('/^(\d+)\. /', $section, $match)) {
        $updatedSections[] = $section;
        continue;
    }

    $number = (int) $match[1];
    if (! isset($replacements[$number])) {
        $updatedSections[] = $section;
        continue;
    }

    $updated = preg_replace(
        '/Success Response:\n.*?(?=\n\nError Responses:|\n\nNotes:|\n\n={10,}|\z)/s',
        $replacements[$number],
        $section,
        1,
        $count
    );

    if ($updated === null || $count !== 1) {
        fwrite(STDERR, "Warning: could not replace endpoint {$number}.\n");
        $updatedSections[] = $section;
        continue;
    }

    $replacedCount++;
    $updatedSections[] = $updated;
}

$updatedWorkflow = implode("\n", $updatedSections);
file_put_contents($workflowPath, $updatedWorkflow);

$capturedDir = dirname($capturedPath);
if (! is_dir($capturedDir)) {
    mkdir($capturedDir, 0777, true);
}

file_put_contents($capturedPath, json_encode($captured, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "Updated POSTMAN_TEST_WORKFLOW.txt with {$replacedCount} captured response sections (".count($captured)." JSON/binary payloads).\n";
