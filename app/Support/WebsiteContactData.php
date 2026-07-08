<?php

namespace App\Support;

readonly class WebsiteContactData
{
    /**
     * @param  array{name: string, email: string, phone?: string|null, message: string}  $data
     */
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone,
        public string $message,
    ) {}

    /**
     * @param  array{name: string, email: string, phone?: string|null, message: string}  $data
     */
    public static function fromValidated(array $data): self
    {
        return new self(
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['message'],
        );
    }
}
