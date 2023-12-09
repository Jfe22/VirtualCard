<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VcardResource extends JsonResource
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
          'phone_number' => $this->phone_number,
          'name' => $this->name,
          'email' => $this->email,
          'photo_url' => $this->photo_url,
          'blocked' => $this->blocked,
          'balance' => $this->balance,
          'max_debit' => $this->max_debit,
        ];
    }
}
