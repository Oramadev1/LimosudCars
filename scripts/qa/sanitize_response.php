<?php

declare(strict_types=1);

/**
 * Remove secrets from captured API payloads before writing docs or local artifacts.
 *
 * @return array<string, mixed>|array<int, mixed>|string|null
 */
function sanitize_qa_response_payload(mixed $payload, int $endpointNumber): mixed
{
    if (! is_array($payload)) {
        return is_string($payload)
            ? sanitize_qa_response_string($payload)
            : $payload;
    }

    if ($endpointNumber === 9 && isset($payload['access_token']) && is_string($payload['access_token'])) {
        $payload['access_token'] = '<REDACTED_QA_TOKEN>';
    }

    if (isset($payload['admin_token']) && is_string($payload['admin_token'])) {
        $payload['admin_token'] = '<REDACTED_QA_TOKEN>';
    }

    foreach ($payload as $key => $value) {
        if (is_array($value)) {
            $payload[$key] = sanitize_qa_response_payload($value, $endpointNumber);
        } elseif (is_string($value)) {
            $payload[$key] = sanitize_qa_response_string($value);
        }
    }

    return $payload;
}

function sanitize_qa_response_string(string $value): string
{
    $value = preg_replace('/\b\d+\|[A-Za-z0-9]+\b/', '<REDACTED_QA_TOKEN>', $value) ?? $value;

    return $value;
}
