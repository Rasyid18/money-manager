<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'type' => $this->type,
            'date' => $this->date,
            'amount' => $this->amount,
            'items' => $this->items,
            'place' => $this->place,
            'notes' => $this->notes,
            'transactionCategory' => new TransactionCategoryResource($this->transactionCategory),
            'account' => new AccountResource($this->fromAccount),
            'destinationAccount' => $this->toAccount ? new AccountResource($this->toAccount) : [],
            'budget' => $this->budget ? new BudgetResource($this->budget) : [],
            'created' => $this->created_at,
            'updated' => $this->updated_at,
        ];
    }
}
