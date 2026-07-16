<?php

namespace App\Http\Requests\Concerns;

use App\Support\InputSanitizer;

trait SanitizesInput
{
    protected function sanitizePlainText(?string $value, int $maxLength = 255): ?string
    {
        return InputSanitizer::plainText($value, $maxLength);
    }

    protected function sanitizeEmail(?string $value, int $maxLength = 255): ?string
    {
        return InputSanitizer::email($value, $maxLength);
    }

    protected function sanitizeMultilineText(?string $value, int $maxLength = 5000): ?string
    {
        return InputSanitizer::multilineText($value, $maxLength);
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, int>  $plainTextFields
     * @return array<string, mixed>
     */
    protected function sanitizePlainTextFields(array $data, array $plainTextFields): array
    {
        foreach ($plainTextFields as $field => $maxLength) {
            if (! array_key_exists($field, $data)) {
                continue;
            }

            $value = $data[$field];
            if (is_string($value) || $value === null) {
                $data[$field] = $this->sanitizePlainText($value, $maxLength);
            }
        }

        return $data;
    }
}
