<?php

namespace App\Http\Requests\Public\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class ProcessCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_type' => 'required|in:qris,gopay,shopeepay,bank_transfer,echannel',
            'bank' => 'required_if:payment_type,bank_transfer|nullable|in:bca,bni,bri',
        ];
    }
}
