<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePantryItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->id === $this->route('pantryItem')->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:255'],
            'purchase_date' => ['required', 'date', 'before_or_equal:today'],
            'expiration_date' => ['nullable', 'date', 'after:purchase_date'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,jpg,png,webp'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the item name.',
            'category_id.exists' => 'Please select a valid category.',
            'quantity.required' => 'Please enter the quantity.',
            'quantity.min' => 'Quantity must be a positive number.',
            'unit.required' => 'Please select a unit.',
            'purchase_date.required' => 'Please enter the purchase date.',
            'purchase_date.before_or_equal' => 'Purchase date cannot be in the future.',
            'expiration_date.after' => 'Expiration date must be after the purchase date.',
            'location_id.exists' => 'Please select a valid storage location.',
            'photo.image' => 'The file must be an image.',
            'photo.max' => 'The image must not be larger than 2MB.',
        ];
    }
}
