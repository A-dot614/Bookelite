<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $book = $this->route('ecommerce');

        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'category' => ['nullable', 'string', 'max:100'],
            'genre' => ['nullable', 'string', 'max:100'],
            'stock' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'pages' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'language' => ['nullable', 'string', 'max:50'],
            'isbn' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('ecommerces', 'isbn')->ignore($book?->id),
            ],
            'sku' => [
                'nullable',
                'string',
                'max:64',
                Rule::unique('ecommerces', 'sku')->ignore($book?->id),
            ],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(['active', 'draft', 'archived'])],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:4096'],
            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => ['image', 'mimes:jpeg,png,webp', 'max:4096'],
        ];
    }
}