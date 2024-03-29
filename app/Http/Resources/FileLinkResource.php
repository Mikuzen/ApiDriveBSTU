<?php

namespace App\Http\Resources;

use App\Models\FileLink;
use Illuminate\Http\Resources\Json\JsonResource;

class FileLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'link' => $this->link,
        ];
    }
}
