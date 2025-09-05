<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        Log::debug(json_encode($this));
        return [
            'id' => $this->id,
            'name' => $this->name,
            'starting_balance' => $this->starting_balance,
            'balance' => $this->current_balance,
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
