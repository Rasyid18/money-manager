<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'starting_balance' => $this->starting_balance,
            'balance' => $this->current_balance,
            'notes' => $this->notes,
            'parent' => $this->whenLoaded('parentAccount', function () {
                return $this->parentAccount ? [
                    'id' => $this->parentAccount->id,
                    'name' => $this->parentAccount->name,
                ] : null;
            }),
            'children' => self::collection($this->whenLoaded('children')),
            'created' => $this->created_at,
            'updated' => $this->updated_at,
        ];
    }
}
