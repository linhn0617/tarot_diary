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

    public function redirect(string $provider): JsonResponse
    {
        /** @var AbstractProvider $driver */
        $driver = Socialite::driver($provider);

        $url = $driver->stateless()->redirect()->getTargetUrl();

        return $this->responseWithData('Redirect URL generated successfully.', [
            'url' => $url,
        ]);
    }

    // 模擬前端去取得 access token
    public function callback(string $provider): string
    {
        /** @var AbstractProvider $driver */
        $driver = Socialite::driver($provider);

        /** @var SocialiteUser $socialiteUser */
        $socialiteUser = $driver->stateless()->user();

        return $socialiteUser->token;
    }

    public function login(Request $request, SocialAuthService $socialAuthService, string $provider): JsonResponse
    {
        /** @var AbstractProvider $driver */
        $driver = Socialite::driver($provider);

        // 根據前端傳送的 access token 取得使用者資料
        $accessToken = $request->input('access_token');

        $socialiteUser = $driver->stateless()->userFromToken($accessToken);

        $data = $socialAuthService->handleCallback($provider, $socialiteUser);

        return $this->responseWithData('Authenticated successfully.', $data);
    }
}
