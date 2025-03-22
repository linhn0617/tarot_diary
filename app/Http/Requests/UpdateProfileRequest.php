<?php

namespace App\Http\Requests;

use App\Enums\GenderType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateProfileRequest extends FormRequest
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
            'name' => ['sometimes', 'string'],
            'gender' => ['sometimes', new Enum(GenderType::class)],
            'birth_date' => ['sometimes', 'date'],
            'password' => ['sometimes', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '使用者名稱',
            'gender' => '性別',
            'birth_date' => '生日',
            'password' => '密碼',
        ];
    }
}
