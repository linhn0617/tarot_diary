<?php

namespace App\Http\Controllers;

use App\Services\SocialAuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User as SocialiteUser;

class SocialAuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private SocialAuthService $socialAuthService
    ) {}

    public function redirect(string $provider): JsonResponse
    {
        try {
            /** @var AbstractProvider $driver */
            $driver = Socialite::driver($provider);

            $url = $driver->stateless()->redirect()->getTargetUrl();

            return $this->responseWithData('成功生成 redirect URL', [
                'url' => $url,
            ]);
        } catch (\Exception $e) {
            return $this->error('生成 redirect URL 失敗: '.$e->getMessage(), 500);
        }
    }

    // 模擬前端去取得 access token
    public function callback(string $provider): JsonResponse
    {
        try {
            /** @var AbstractProvider $driver */
            $driver = Socialite::driver($provider);

            /** @var SocialiteUser $socialiteUser */
            $socialiteUser = $driver->stateless()->user();

            return $this->responseWithData('順利取得 access token', ['access_token' => $socialiteUser->token]);
        } catch (\Exception $e) {
            return $this->error('取得 access token 失敗: '.$e->getMessage(), 500);
        }
    }

    public function login(Request $request, string $provider): JsonResponse
    {
        try {
            /** @var AbstractProvider $driver */
            $driver = Socialite::driver($provider);

            // 根據前端傳送的 access token 取得使用者資料
            $accessToken = $request->input('access_token');

            $socialiteUser = $driver->stateless()->userFromToken($accessToken);

            $data = $this->socialAuthService->handleCallback($provider, $socialiteUser);

            return $this->responseWithData('註冊成功', $data);
        } catch (\Exception $e) {
            return $this->error('註冊失敗: '.$e->getMessage(), 500);
        }
    }
}
