<?php

namespace App\Http\Requests\Events;

use Illuminate\Foundation\Http\FormRequest;

class EventStoreRequest extends FormRequest
{
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
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'date' => 'required|date',
            'time' => 'required',
            'is_active' => 'required|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'thumbnail' => 'thumbnail',
            'name' => 'nama',
            'description' => 'deskripsi',
            'price' => 'harga',
            'date' => 'tanggal',
            'time' => 'waktu',
            'is_active' => 'status aktif',
        ];
    }

    public function messages()
    {
        return [
            'event_id.required' => 'Field :attribute wajib diisi.',
            'event_id.exists' => ':attribute yang dipilih tidak valid.',
            'head_of_family_id.required' => 'Field :attribute wajib diisi.',
            'head_of_family_id.exists' => ':attribute yang dipilih tidak valid.',
            'quantity.required' => 'Field :attribute wajib diisi.',
            'quantity.integer' => ':attribute harus berupa angka bulat.',
            'quantity.min' => ':attribute minimal harus :min.',
            'total_price.required' => 'Field :attribute wajib diisi.',
            'total_price.numeric' => ':attribute harus berupa angka.',
            'total_price.min' => ':attribute minimal harus :min.',
            'payment_status.required' => 'Field :attribute wajib diisi.',
            'payment_status.in' => ':attribute yang dipilih tidak valid. Nilai yang diizinkan: pending, completed, failed.',
        ];
    }
}
