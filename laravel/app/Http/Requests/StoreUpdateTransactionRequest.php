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
          'vcard' => 'required|string|exists:vcards,phone_number',
          'type' => 'required|in:C,D',
          'value' => 'required|numeric',
          'payment_type' => 'required|in:VCARD,MBWAY,IBAN,MB,VISA',
          'payment_reference' => 'required|string',
          'category_id' => 'nullable|numeric|exists:categories,id',
          'description' => 'nullable|string|max:255',

          //new boolean val that shows if the transaction is a pair transaction
          //we send 2 requests in vue, one for the transaction and another for the pair transaction
          'is_pair' => 'nullable|boolean',
        ];
    } }
