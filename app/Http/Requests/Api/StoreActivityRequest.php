<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'location' => ['required', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'interests' => ['nullable', 'array', 'max:5'],
            'interests.*' => ['integer', 'exists:interests,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'interests.max' => 'Du kannst höchstens 5 Interessen auswählen.',
            'banner.max' => 'Das Banner-Bild darf höchstens 5 MB groß sein.',
        ];
    }
}
