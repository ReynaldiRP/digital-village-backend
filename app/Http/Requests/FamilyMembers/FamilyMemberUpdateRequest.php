<?php

namespace App\Http\Requests\FamilyMembers;

use Illuminate\Foundation\Http\FormRequest;

class FamilyMemberUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'head_of_family_id' => 'sometimes|exists:head_of_families,id',
            'user_id' => [
                'sometimes',
                'exists:users,id',
                'unique:family_members,user_id,' . request()->route('family_member')
            ],
            'profile_picture' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
            'identify_number' => [
                'sometimes',
                'string',
                'max:20',
                'unique:family_members,identify_number,' . request()->route('family_member')
            ],
            'gender' => 'sometimes|string|in:male,female',
            'birth_date' => 'sometimes|date',
            'phone_number' => 'sometimes|string|max:15',
            'occupation' => 'sometimes|string|max:100',
            'marital_status' => 'sometimes|string|in:single,married',
            'relation' => 'sometimes|string|max:50|in:child,wife,husband',
        ];
    }

    public function attributes()
    {
        return [
            'head_of_family_id' => 'ID Kepala Keluarga',
            'user_id' => 'ID Pengguna',
            'profile_picture' => 'Foto Profil',
            'identify_number' => 'Nomor Identitas',
            'gender' => 'Jenis Kelamin',
            'birth_date' => 'Tanggal Lahir',
            'phone_number' => 'Nomor Telepon',
            'occupation' => 'Pekerjaan',
            'marital_status' => 'Status Perkawinan',
            'relation' => 'Hubungan',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa string.',
            'max'      => ':attribute maksimal :max karakter.',
            'date'     => ':attribute harus berupa tanggal yang valid.',
            'exists'   => ':attribute tidak ditemukan di database.',
            'unique'   => ':attribute sudah digunakan.',
            'in'       => ':attribute tidak valid.',
            'image'    => ':attribute harus berupa file gambar.',
        ];
    }
}
