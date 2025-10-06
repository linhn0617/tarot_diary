<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'tarot_specification_id' => ['nullable', 'integer', 'exists:tarot_specifications,id'],
            'tarot_id' => ['required_without:tarot_specification_id', 'integer', 'exists:tarots,id'],
            'is_upright' => ['required_without:tarot_specification_id', 'boolean'],
            'user_entry_text' => ['required', 'string', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'tarot_specification_id.exists' => '無效的塔羅牌正逆位資料，請重新抽牌。',
            'tarot_id.required_without' => '請提供塔羅牌編號或正逆位資料。',
            'tarot_id.exists' => '找不到指定的塔羅牌。',
            'is_upright.required_without' => '請提供塔羅牌的正逆位資訊。',
            'is_upright.boolean' => '正逆位欄位僅接受 true/false 或 1/0。',
            'user_entry_text.required' => '請輸入您的日記內容',
            'user_entry_text.max' => '日記內容最多200字',
        ];
    }
}
