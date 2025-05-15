<?php

namespace App\Services;

use App\Models\User;

class ProfileService
{
    /**
     * 更新使用者個人資料（名稱、性別、生日）
     */
    public function updateProfile(User $user, array $data): void
    {
        $user->update([
            'name' => $data['name'],
            'gender' => $data['gender'],
            'birth_date' => $data['birth_date'],
        ]);
    }
}
