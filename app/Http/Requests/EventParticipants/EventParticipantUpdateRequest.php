<?php

namespace App\Http\Requests\EventParticipants;

use Illuminate\Foundation\Http\FormRequest;

class EventParticipantUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => 'sometimes|exists:events,id',
            'head_of_family_id' => 'sometimes|exists:head_of_families,id',
            'quantity' => 'sometimes|integer|min:1',
            'payment_status' => 'sometimes|in:pending,completed,failed',
        ];
    }

    public function attributes()
    {
        return [
            'event_id' => 'Event',
            'head_of_family_id' => 'Kepala Keluarga',
            'quantity' => 'Kuantitas',
            'payment_status' => 'Status Pembayaran',
        ];
    }

    public function messages()
    {
        return [
            'event_id.exists' => ':attribute yang dipilih tidak valid.',
            'head_of_family_id.exists' => ':attribute yang dipilih tidak valid.',
            'quantity.integer' => ':attribute harus berupa angka bulat.',
            'quantity.min' => ':attribute minimal harus :min.',
            'payment_status.in' => ':attribute yang dipilih tidak valid. Nilai yang diizinkan: pending, completed, failed.',
        ];
    }
}
