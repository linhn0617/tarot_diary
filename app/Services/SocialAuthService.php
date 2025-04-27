<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserSocialAccount;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialAuthService
{
    /**
     * 處理第三方登入後的 callback 資料，
     * 根據 provider 與第三方 user 資訊，找出或建立對應的系統使用者，
     * 並回傳 JWT token。
     */
    public function handleCallback(string $provider, SocialiteUser $socialiteUser): array
    {
        $email = $socialiteUser->getEmail();

        // 1. 先從 user_social_accounts 表找有沒有綁定過的帳號
        $existingUserSocialAccount = $this->findExistingUserSocialAccount($provider, $socialiteUser);

        if ($existingUserSocialAccount) {
            // 已經綁定過，直接登入
            $user = $existingUserSocialAccount->user()->firstOrFail();
            $token = $this->loginAndCreateToken($user);

            return [
                'token' => $token,
                'need_profile_setup' => false,
                'name' => $user->name,
            ];
        }

        // 2. 查 User 表有沒有用這個 email 註冊過
        $existingUser = $this->findExistingUser($email);

        if ($existingUser) {
            // 有 email，但沒綁定 social 帳號 → 建立關聯
            $this->linkSocialAccountToUser($existingUser, $provider, $socialiteUser);

            $token = $this->loginAndCreateToken($existingUser);

            return [
                'token' => $token,
                'need_profile_setup' => false,
                'name' => $existingUser->name,
            ];
        }

        // 3. 真的什麼都沒有，建立新 User 並關聯 social 資料
        $newUser = $this->createNewUser($socialiteUser);

        $this->linkSocialAccountToUser($newUser, $provider, $socialiteUser);

        $token = $this->loginAndCreateToken($newUser);

        return [
            'token' => $token,
            'need_profile_setup' => true,
            'name' => $newUser->name,
        ];
    }

    /**
     * 查詢 user_social_accounts 表，看是否已有綁定的社群帳號。
     */
    private function findExistingUserSocialAccount(string $provider, SocialiteUser $socialiteUser): ?UserSocialAccount
    {
        return UserSocialAccount::where('provider_id', $socialiteUser->getId())
            ->where('provider', $provider)
            ->first();
    }

    /**
     * 查詢 User 表，看 email 是否已存在。
     */
    private function findExistingUser(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * 將第三方登入資料與現有 User 進行綁定，存入 user_social_accounts。
     */
    private function linkSocialAccountToUser(User $user, string $provider, SocialiteUser $socialiteUser): void
    {
        UserSocialAccount::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialiteUser->getId(),
            'email' => $socialiteUser->getEmail(),
        ]);
    }

    /**
     * 第一次第三方登入，建立新的 User。
     */
    private function createNewUser(SocialiteUser $socialiteUser): User
    {
        return User::create([
            'name' => $socialiteUser->getName() ?? '',
            'email' => $socialiteUser->getEmail(),
            'email_verified_at' => now(),
            'self_profile' => '',
            'profile_image_path' => '',
        ]);
    }

    /**
     * 將指定的 User 登入系統，並產生 JWT token。
     */
    private function loginAndCreateToken(User $user): string
    {
        Auth::login($user);

        /** @phpstan-ignore-next-line */
        return JWTAuth::fromUser($user);
    }
}
