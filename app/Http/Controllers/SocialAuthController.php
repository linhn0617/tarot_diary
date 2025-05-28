<?php

namespace App\Http\Controllers;

use App\Services\SocialAuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;

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

    // 前端傳送 access token 來註冊或登入
    public function callback(Request $request, string $provider): JsonResponse
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
