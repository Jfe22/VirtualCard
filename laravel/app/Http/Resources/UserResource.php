<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
          'user_type' => $this->user_type,
          'username' => $this->username,
          'name' => $this->name,
          'email' => $this->email,
          'photo_url' => $this->photo_url,
        ];
    }
}
