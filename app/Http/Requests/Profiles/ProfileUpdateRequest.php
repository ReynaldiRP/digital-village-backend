<?php

namespace App\Http\Requests\Profiles;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'about' => 'nullable|string',
            'headman' => 'nullable|string|max:255',
            'people' => 'nullable|integer',
            'agricultural_area' => 'nullable|integer',
            'total_area' => 'nullable|integer',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function attributes()
    {
        return [
            'thumbnail' => 'thumbnail',
            'name' => 'nama desa',
            'about' => 'tentang desa',
            'headman' => 'kepala desa',
            'people' => 'jumlah penduduk',
            'agricultural_area' => 'luas lahan pertanian',
            'total_area' => 'luas wilayah',
            'images' => 'gambar profil desa',
            'images.*' => 'gambar profil desa',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'integer' => ':attribute harus berupa angka.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berupa file dengan format: :values.',
            'array' => ':attribute harus berupa array.',
        ];
    }
}
