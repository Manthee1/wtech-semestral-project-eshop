<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role == 'Admin';
    }

    // Change the text for the validation error messages
    public function messages(): array
    {
        return [
            'make_id.required' => 'The make field is required.',
            'make_id.exists' => 'The selected make is invalid.',
            'model_id.required' => 'The model field is required.',
            'model_id.exists' => 'The selected model is invalid.',
            'year.required' => 'The year field is required.',
            'year.integer' => 'The year must be an integer.',
            'year.min' => 'The year must be at least 0.',
            'year.unique' => 'There already exists a product with the same make, model, and year.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'make_id' => 'required|exists:product_makes,id',
            'model_id' => 'required|exists:product_models,id',
            'year' => 'required|integer|min:1769|unique:products,year,NULL,id,make_id,' . $this->make_id . ',model_id,' . $this->model_id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'active' => 'boolean',
            'drivetrain_id' => 'required|exists:product_drivetrains,id',
            'body_type_id' => 'required|exists:product_body_types,id',
            'engine_type_id' => 'required|exists:product_engine_types,id',
            'efficiency' => 'nullable|string|max:255',
            'height' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'horse_power' => 'nullable|integer|min:0',
            'passenger_capacity' => 'nullable|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'delete_image[]' => 'nullable|array',
        ];
    }
}
