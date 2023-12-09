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
          'phone_number' => 'required|string|max:9',
          'name' => 'required|string|max:255',
          'email' => 'required|email|max:255',
          'photo_url' => 'nullable|string|max:255',
          'password' => 'required|confirmed|min:3',
          'blocked' => 'required|boolean',
          'balance' => 'required|numeric',
          'max_debit' => 'required|numeric',
        ];
    }
}
