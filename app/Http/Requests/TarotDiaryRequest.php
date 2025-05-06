<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TarotDiaryRequest extends FormRequest
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
            'tarot_id' => ['required', 'integer'],
            'user_entry_text' => ['required', 'string', 'max:200'],
        ];
    }

    public function messages()
    {
        return [
            'user_entry_text.required' => '請輸入您的日記內容',
            'user_entry_text.max' => '日記內容最多200字',
        ];
    }
}
