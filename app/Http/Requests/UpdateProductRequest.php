<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role == 'Admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'price' => 'numeric|min:0',
            'description' => 'string',
            'stock' => 'integer|min:0',
            'active' => 'boolean',
            'make_id' => 'exists:product_makes,id',
            'model' => 'string|max:60',
            'drivetrain' => 'string|max:60',
            'body_type' => 'string|max:60',
            'efficiency' => 'numeric|max:60',
            'engine_type' => 'string|max:60',
            'height' => 'numeric|min:0',
            'width' => 'numeric|min:0',
            'length' => 'numeric|min:0',
            'horse_power' => 'integer|min:0',
            'passenger_capacity' => 'integer|min:0',
            'year' => 'integer|min:1769',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'delete_image[]' => 'array',
        ];

        // Loop through the fields and reomve the ones that are not filled
        foreach ($rules as $key => $value) {
            if (!$this->filled($key)) {
                unset($rules[$key]);
            }
        }

        return $rules;
    }
}
