<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadCreatedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'meta' => [
                'success' => true,
                'errors' => [],
            ],
            'data' => [
                'id' => $this->resource['lead']->id,
                'name' => $this->resource['lead']->name,
                'source' => $this->resource['lead']->source,
                'owner' => $this->resource['lead']->owner,
                'created_by' => $this->resource['lead']->created_by,
                'created_at' => $this->resource['lead']->created_at,
            ],
        ];
    }
}
