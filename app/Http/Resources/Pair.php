<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Pair extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $instances = explode(',', substr($this->instances, 1, -1));
        return [
            'name' => $this->name,
            'instances' => $instances,
        ];
    }
}
