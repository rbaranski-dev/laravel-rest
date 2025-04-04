<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuisnessResource extends JsonResource
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
            'number' => $this->number,
            'address' => $this->address,
            'city' => $this->city,
            'zip_code' => $this->zip_code,
            'employees' => EmployeeResource::collection($this->whenLoaded('employees')),
        ];
    }
}
