<?php

namespace App\Http\Requests;

use App\Models\User;

use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardExpirationMonth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AccountUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {

        $basicRules = [
            'first_name' => ['string', 'max:100'],
            'last_name' => ['string', 'max:100'],
            'phone_number' => ['string', 'max:20'],
            'email' => ['string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];

        $passwordRules = [
            'current_password' => ['required', 'string', 'min:8', function ($attribute, $value, $fail) {
                if (!Hash::check($value, $this->user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'new_password' => ['required', 'string', 'min:8', 'different:current_password'],
            'repeat_new_password' => ['required', 'string', 'same:new_password'],
        ];

        $deliveryRules = [
            'street_address' => ['string', 'max:255'],
            'city' => ['string', 'max:60'],
            'country' => ['string', 'max:2'],
        ];

        $cardRules = [
            'card_name' => ['required', 'string', 'max:201'],
            'card_number' => ['required', new CardNumber],
            'card_expiration_year' => ['required', new CardExpirationYear($this->get('card_expiration_month'))],
            'card_expiration_month' => ['required', new CardExpirationMonth($this->get('card_expiration_year'))],
            'cvv' => ['required', new CardCvc($this->get('card_number'))]
        ];

        $rules = array_merge($basicRules, $deliveryRules);
        // If the new_password field is present, we know the user is trying to update their password
        if ($this->filled('new_password')) {
            $rules = array_merge($rules, $passwordRules);
        }

        // If the card_number field is present, we know the user is trying to update their card details
        if ($this->filled('card_number') || $this->filled('card_expiration_year') || $this->filled('card_expiration_month') || $this->filled('cvv')) {
            $rules = array_merge($rules, $cardRules);
        }


        return $rules;
    }
}
