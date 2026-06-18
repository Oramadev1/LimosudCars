<?php

namespace App\Http\Resources;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Expense
 */
class ExpenseResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle' => $this->whenLoaded('vehicle', fn (): ?array => $this->vehicle ? [
                'id' => $this->vehicle->id,
                'name' => $this->vehicle->name,
                'slug' => $this->vehicle->slug,
                'plate_number' => $this->vehicle->plate_number,
            ] : null),
            'expense_category' => $this->whenLoaded('expenseCategory', fn (): array => [
                'id' => $this->expenseCategory->id,
                'name' => $this->expenseCategory->name,
                'slug' => $this->expenseCategory->slug,
            ]),
            'amount' => $this->amount,
            'expense_date' => $this->expense_date,
            'description' => $this->description,
            'has_invoice' => $this->invoice_path !== null,
            'created_by' => $this->whenLoaded('createdBy', fn (): ?array => $this->createdBy ? [
                'id' => $this->createdBy->id,
                'name' => $this->createdBy->name,
                'email' => $this->createdBy->email,
            ] : null),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
