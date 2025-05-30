<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getCurrentUser(): array
    {
        // 取得當前登入的使用者
        $user = Auth::user();

        return $user->toArray();
    }

    public function updateProfile($validatedData): array
    {
        // 取得當前登入的使用者
        $user = Auth::user();

        // 更新使用者資料
        $user->name = $validatedData['name'];
        $user->gender = $validatedData['gender'];
        $user->birth_date = $validatedData['birth_date'];
        if (isset($validatedData['password']) && ! empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->save();

        return $user->toArray();
    }
}
