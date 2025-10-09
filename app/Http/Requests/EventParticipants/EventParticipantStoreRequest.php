<?php

namespace App\Http\Requests\EventParticipants;

use Illuminate\Foundation\Http\FormRequest;

class EventParticipantStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => 'required|exists:events,id',
            'head_of_family_id' => 'required|exists:head_of_families,id',
            'quantity' => 'required|integer|min:1',
            'payment_status' => 'required|in:pending,completed,failed',
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
            'event_id.required' => 'The :attribute field is required.',
            'event_id.exists' => 'The selected :attribute is invalid.',
            'head_of_family_id.required' => 'The :attribute field is required.',
            'head_of_family_id.exists' => 'The selected :attribute is invalid.',
            'quantity.required' => 'The :attribute field is required.',
            'quantity.integer' => 'The :attribute must be an integer.',
            'quantity.min' => 'The :attribute must be at least :min.',
            'payment_status.required' => 'The :attribute field is required.',
            'payment_status.in' => 'The selected :attribute is invalid. Allowed values are: pending, completed, failed.',
        ];
    }
}
