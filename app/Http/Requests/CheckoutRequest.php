<?php

namespace App\Http\Requests;

use App\Services\Payment\PaymentManager;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $countries = config('ecommerce.countries', []);

        return [
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_email' => ['required', 'email', 'max:255'],
            'shipping_phone' => ['nullable', 'string', 'max:50'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_country' => [
                'required',
                'string',
                'max:100',
                Rule::in($countries),
            ],
            'shipping_zip' => ['required', 'string', 'max:20'],
            'payment_method' => [
                'required',
                'string',
                Rule::in(app(PaymentManager::class)->availableMethods()),
            ],
            'notes' => ['nullable', 'string', 'max:1000'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_country.in' => 'Please choose a valid shipping country.',
            'payment_method.in' => 'The selected payment method is not currently available.',
        ];
    }
}