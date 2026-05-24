<?php

namespace App\Http\Requests\Api\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $language = (string) $this->input('preferred_language', 'tr');

        return [
            'preferred_language' => ['required', 'string', 'max:8'],
            'preferred_arabic_font' => ['nullable', Rule::in(['amiri', 'noto_naskh', 'scheherazade'])],
            'preferred_tafsir_id' => ['nullable', 'integer', 'min:1'],
            'preferred_tafsir_name' => ['nullable', 'string', 'max:200'],
            'last_read_sura' => ['nullable', 'integer', 'between:1,114'],
            'last_read_aya' => ['nullable', 'integer', 'min:1'],
            'selected_meal_keys' => ['sometimes', 'array'],
            'selected_meal_keys.*' => [
                'string',
                Rule::exists('verse_translations', 'meal_key')->where(fn ($query) => $query->where('language', $language)),
            ],
        ];
    }
}
