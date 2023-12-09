<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
    //    return parent::toArray($request);
        return [
          'id' => $this->id,
          'vcard' => $this->vcard,
          'date' => $this->date,
          'datetime' => $this->datetime,
          'type' => $this->type,
          'old_balance' => $this->old_balance,
          'new_balance' => $this->new_balance,
          'payment_type' => $this->payment_type,
          'payment_reference' => $this->payment_reference,
          'pair_transaction' => $this->pair_transaction,
          'pair_vcard' => $this->pair_vcard,
          'category_id' => $this->category_id,
          'description' => $this->description,
        ];
    }
}
