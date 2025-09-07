<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionCategoryResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->type,
            'icon' => $this->icon,
            'color' => $this->color,
            'sort_order' => $this->sort_order,
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
