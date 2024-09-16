<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LeadCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'meta' => [
                'success' => true,
                'errors' => [],
            ],
            'data' => $this->collection->map(function ($lead) {
                return [
                    'id' => $lead['id'],
                    'name' => $lead['name'],
                    'source' => $lead['source'],
                    'owner' => $lead['owner'],
                    'created_by' => $lead['created_by'],
                    'created_at' => Carbon::parse($lead['created_at'])->toDateTimeString(), // Asume que ya está en el formato adecuado
                ];
            }),
        ];
    }
}
