<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by policy
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'theme_id' => 'sometimes|nullable|exists:themes,id',
            'content' => 'sometimes|array',
            'content.hero_title' => 'sometimes|string|max:255',
            'content.hero_subtitle' => 'sometimes|string|max:500',
            'content.about_heading' => 'sometimes|string|max:255',
            'content.about_text' => 'sometimes|string',
            'content.services_heading' => 'sometimes|string|max:255',
            'content.services' => 'sometimes|array',
            'content.services.*.title' => 'required_with:content.services|string|max:255',
            'content.services.*.description' => 'required_with:content.services|string',
            'content.contact_heading' => 'sometimes|string|max:255',
            'content.contact_email' => 'sometimes|email|max:255',
            'content.contact_phone' => 'sometimes|string|max:20',
            'images' => 'sometimes|array',
            'custom_colors' => 'sometimes|nullable|array',
            'custom_colors.primary' => 'sometimes|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'custom_colors.secondary' => 'sometimes|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'custom_colors.accent' => 'sometimes|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'custom_colors.background' => 'sometimes|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'custom_colors.text' => 'sometimes|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'custom_colors.heading' => 'sometimes|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_published' => 'sometimes|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'content.hero_title.max' => 'The hero title must not exceed 255 characters.',
            'custom_colors.*.regex' => 'The color must be a valid hex color code (e.g., #FFFFFF).',
        ];
    }
}


