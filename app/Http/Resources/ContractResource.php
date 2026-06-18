<?php

namespace App\Http\Resources;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

/**
 * @mixin Contract
 */
class ContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reservation_id' => $this->reservation_id,
            'contract_number' => $this->contract_number,
            'status' => $this->lookup('status'),
            'has_pdf' => filled($this->pdf_path),
            'has_signed_pdf' => filled($this->signed_pdf_path),
            'generated_by' => $this->whenLoaded('generatedBy', fn (): ?array => $this->generatedBy ? [
                'id' => $this->generatedBy->id,
                'name' => $this->generatedBy->name,
                'email' => $this->generatedBy->email,
            ] : null),
            'generated_at' => $this->generated_at,
            'signed_at' => $this->signed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * @return array{id: int|null, name: string|null, slug: string|null}|MissingValue
     */
    private function lookup(string $relationship): array|MissingValue
    {
        return $this->whenLoaded($relationship, fn (): array => [
            'id' => $this->{$relationship}->id,
            'name' => $this->{$relationship}->name,
            'slug' => $this->{$relationship}->slug,
        ]);
    }
}
