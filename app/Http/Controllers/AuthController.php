<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Services\EmailVerifyService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private AuthService $authService,
        private EmailVerifyService $emailVerifyService
    ) {}

    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        try {
            // 驗證傳入的資料
            $validatedData = $registerRequest->validated();
            // 呼叫註冊的service
            $user = $this->authService->register($validatedData);

            // 回傳註冊成功的訊息
            return $this->success('註冊成功，請至信箱點選驗證信以開通帳戶', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function verifyEmail(int $id, string $hash)
    {
        try {
            $this->emailVerifyService->verifyEmail($id, $hash);

            return redirect('/');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function login(LoginRequest $loginRequest)
    {
        try {
            // 驗證傳入的資料
            $validatedData = $loginRequest->validated();
            // 呼叫登入的service
            $token = $this->authService->login($validatedData);

            return $this->responseWithToken('登入成功', $token, Response::HTTP_OK);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            Auth::logout();

            return $this->success('登出成功', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function refresh(): JsonResponse
    {
        try {
            // @phpstan-ignore-next-line
            $token = Auth::refresh();

            return $this->responseWithToken('刷新成功', $token, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
