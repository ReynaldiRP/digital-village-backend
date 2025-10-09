<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginStoreRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'Email',
            'password' => 'Kata Sandi',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => ':attribute wajib diisi.',
            'email.email' => ':attribute tidak valid.',
            'password.required' => ':attribute wajib diisi.',
            'password.string' => ':attribute harus berupa string.',
            'password.min' => ':attribute minimal :min karakter.',
        ];
    }
}
