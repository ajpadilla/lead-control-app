<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthenticateUserSuccessResource extends JsonResource
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
            'data' => [
                'token' => $this->resource['token'],
                'minutes_to_expire' => 1440, // Set token expiration time
            ],
        ];
    }
}
