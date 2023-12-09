<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request. 
     */ 
    public function authorize(): bool 
    { 
      return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
          'id' => 'required|numeric',
          'vcard' => 'required|string|exists:vcards,phone_number',
          'date' => 'required|date',
          'datetime' => 'required|date',
          'type' => 'required|in:C,D',
          'value' => 'required|numeric',
          'old_balance' => 'required|numeric',
          'new_balance' => 'required|numeric',
          'payment_type' => 'required|in:VCARD,MBWAY,IBAN,MB,VISA',
          'payment_reference' => 'required|string',
          'pair_transaction' => 'nullable|numeric',
          'pair_vcard' => 'nullable|string|exists:vcards,phone_number',
          'category_id' => 'nullable|numeric|exists:categories,id',
          'description' => 'nullable|string|max:255',
        ];
    } }
