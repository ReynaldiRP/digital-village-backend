<?php

namespace App\Http\Requests\FamilyMembers;

use Illuminate\Foundation\Http\FormRequest;

class FamilyMemberStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:family_members,email',
            'password' => 'required|string|min:8',
            'head_of_family_id' => 'required|exists:head_of_families,id',
            'profile_picture' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
            'identify_number' => 'required|string|max:20|unique:family_members,identify_number',
            'gender' => 'required|string|in:male,female',
            'birth_date' => 'required|date',
            'phone_number' => 'required|string|max:15',
            'occupation' => 'nullable|string|max:50',
            'marital_status' => 'required|string|in:single,married',
            'relation' => 'required|string|max:50|in:child,wife,husband',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Kata Sandi',
            'head_of_family_id' => 'ID Kepala Keluarga',
            'profile_picture' => 'Foto Profil',
            'identify_number' => 'Nomor Identitas',
            'gender' => 'Jenis Kelamin',
            'birth_date' => 'Tanggal Lahir',
            'phone_number' => 'Nomor Telepon',
            'occupation' => 'Pekerjaan',
            'marital_status' => 'Status Pernikahan',
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
            'image'    => ':attribute harus berupa gambar.',
            'in'       => ':attribute tidak valid.',
            'unique'   => ':attribute sudah digunakan.',

            'head_of_family_id.exists' => 'ID Kepala Keluarga tidak ditemukan.',
            'user_id.exists'           => 'ID Pengguna tidak ditemukan.',
            'user_id.unique'           => 'ID Pengguna sudah terdaftar sebagai anggota keluarga.',
            'profile_picture.max'      => 'Foto Profil maksimal berukuran 2MB.',
            'identify_number.unique'   => 'Nomor Identitas sudah digunakan.',
        ];
    }
}
