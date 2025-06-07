<?php

namespace App\Services;

use App\Models\User;
use App\Services\AuthService;

class EmailVerifyService
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function verifyEmail(int $id, string $hash): void
    {
        // 進入DB並透過ID搜尋使用者
        $user = $this->authService->findUserById($id);

        // 驗證使用者 ID 和 hash 是否匹配
        if (! $this->verifyEmailHash($user, $hash)) {
            throw new \Exception('驗證失敗.');
        }

        // 判斷使用者是否已經驗證過
        if ($this->isEmailAlreadyVerified($user)) {
            throw new \Exception('使用者已進行過驗證.');
        }

        // 將使用者的 email_verified_at 欄位標記為現在的時間
        $user->markEmailAsVerified();

    }

    private function verifyEmailHash(User $user, string $hash): bool
    {
        return hash_equals($hash, sha1($user->getEmailForVerification()));
    }

    private function isEmailAlreadyVerified(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }
}
