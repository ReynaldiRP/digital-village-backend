<?php

namespace App\Http\Requests\HeadOfFamilies;

use Illuminate\Foundation\Http\FormRequest;

class HeadOfFamilyUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|exists:users,id',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'identify_number' => 'sometimes|string|max:255',
            'gender' => 'sometimes|string|in:male,female',
            'birth_date' => 'sometimes|date',
            'phone_number' => 'sometimes|string|max:20',
            'occupation' => 'sometimes|string|max:255',
            'marital_status' => 'sometimes|string|in:single,married',
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
