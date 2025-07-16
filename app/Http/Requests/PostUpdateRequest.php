<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
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
        return [
            'image'=> ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'title'=> 'required',
            'subtitle' => [
            'required',
            'string',
                function ($attribute, $value, $fail) {
                    $wordCount = str_word_count($value);
                    if ($wordCount > 15) {
                        $fail("The $attribute may not be greater than 15 words. (Current: $wordCount words)");
                    }
                },
            ],
            'content'=> 'required',
            'category_id'=> ['required', 'exists:categories,id'],
            'published_at'=> ['nullable', 'date']
        ];
    }
}
