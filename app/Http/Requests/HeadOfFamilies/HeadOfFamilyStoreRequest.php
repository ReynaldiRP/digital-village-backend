<?php

namespace App\Http\Requests\HeadOfFamilies;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeadOfFamilyStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'identify_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('head_of_families', 'identify_number')->whereNull('deleted_at')
            ],
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'phone_number' => 'required|string|max:20',
            'occupation' => 'required|string|max:255',
            'marital_status' => 'required|in:single,married',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'ID Pengguna',
            'profile_picture' => 'Foto Profil',
            'identify_number' => 'Nomor Identitas',
            'gender' => 'Jenis Kelamin',
            'birth_date' => 'Tanggal Lahir',
            'phone_number' => 'Nomor Telepon',
            'occupation' => 'Pekerjaan',
            'marital_status' => 'Status Pernikahan',
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Kata Sandi',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa string.',
            'max'      => ':attribute maksimal :max karakter.',
            'date'     => ':attribute harus berupa tanggal yang valid.',
            'image'    => ':attribute harus berupa gambar.',

            'user_id.exists'       => 'ID Pengguna tidak ditemukan.',
            'profile_picture.max'  => 'Foto Profil maksimal berukuran 2MB.',
            'gender.in'            => ':attribute tidak valid.',
            'marital_status.in'    => ':attribute tidak valid.',
        ];
    }
}
