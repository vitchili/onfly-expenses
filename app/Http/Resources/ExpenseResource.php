<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'          => $this->resource->id,
            'description' => $this->description,
            'date'        => $this->date,
            'user'        => [
                'id'   => $this->resource->user->id,
                'name' => $this->resource->user->name,
            ],
            'value'       => number_format((float) $this->value, 2, '.', ''),
        ];
    }
}