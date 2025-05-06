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

    public function redirect(string $provider): JsonResponse
    {
        /** @var AbstractProvider $driver */
        $driver = Socialite::driver($provider);

        $url = $driver->stateless()->redirect()->getTargetUrl();

        return $this->responseWithData('Redirect URL generated successfully.', [
            'url' => $url,
        ]);
    }

    public function callback(Request $request, SocialAuthService $socialAuthService, string $provider): JsonResponse
    {
        /** @var AbstractProvider $driver */
        $driver = Socialite::driver($provider);

        $socialiteUser = $driver->stateless()->user();

        $data = $socialAuthService->handleCallback($provider, $socialiteUser);

        return $this->responseWithData('Authenticated successfully.', [
            'data' => $data,
        ]);
    }
}
