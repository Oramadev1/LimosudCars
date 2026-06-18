<?php

namespace App\Http\Resources;

use App\Models\CustomerDocument;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CustomerDocument
 */
class CustomerDocumentResource extends JsonResource
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
            'document_type' => $this->whenLoaded('documentType', fn (): array => [
                'id' => $this->documentType->id,
                'name' => $this->documentType->name,
                'slug' => $this->documentType->slug,
            ]),
            'title' => $this->title,
            'file_path' => $this->file_path,
            'expires_at' => $this->expires_at,
            'created_at' => $this->created_at,
        ];
    }
}
