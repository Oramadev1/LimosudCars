<?php

namespace App\Http\Requests\Public;

use App\Http\Requests\Concerns\SanitizesInput;
use App\Support\ValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class StorePublicContactMessageRequest extends FormRequest
{
    use SanitizesInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge($this->sanitizePlainTextFields($this->all(), [
            'name' => 255,
            'phone' => 50,
        ]));

        if ($this->has('email')) {
            $this->merge([
                'email' => $this->sanitizeEmail($this->input('email')),
            ]);
        }

        if ($this->has('message')) {
            $this->merge([
                'message' => $this->sanitizeMultilineText($this->input('message'), 5000),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', ValidationRules::PERSON_NAME],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50', ValidationRules::PHONE],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }
}
