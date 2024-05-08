<?php

namespace App\Http\Requests;

use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardExpirationMonth;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone_number' => 'string|max:20',
            'email' => 'required|email|max:255',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:60',
            'country' => 'required|string|max:60',
            'payment_type' => 'required|string',
        ];

        // If payment type is not provided
        if (!$this->input('payment_type') || $this->input('payment_type') === 'bank-transfer') {
            return $rules;
        }

        // If payment type is credit card
        $cardRules = [
            'card_name' => ['required', 'string', 'max:201'],
            'card_number' => ['required', new CardNumber],
            'card_expiration_year' => ['required', new CardExpirationYear($this->get('card_expiration_month'))],
            'card_expiration_month' => ['required', new CardExpirationMonth($this->get('card_expiration_year'))],
            'cvv' => ['required', new CardCvc($this->get('card_number'))]
        ];


        return array_merge($rules, $cardRules);
    }
}
