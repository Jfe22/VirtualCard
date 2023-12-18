<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateVcardRequest extends FormRequest
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
          'phone_number' => 'required|string|size:9',
          'name' => 'required|string|max:255',
          'email' => 'required|email|max:255',
          'photo_url' => 'nullable|string|max:255',
          'password' => 'required|string|min:3',
          'confirmation_code' => 'required|string|size:4',
          'blocked' => 'required|boolean',
          'balance' => 'required|numeric',
          'max_debit' => 'required|numeric',
        ];
    }
}
