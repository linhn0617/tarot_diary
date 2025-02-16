<?php

namespace App\Http\Requests;

use App\Enums\GenderType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class RegisterRequest extends FormRequest
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
            'name' => ['required','string'],
            'gender' => ['required', new Enum(GenderType::class)],
            'birth_date' => ['required','date'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '使用者名稱',
            'gender' => '性別',
            'birth_date' => '生日',
            'email' => '電子信箱',
            'password' => '密碼',
        ];
    }
}