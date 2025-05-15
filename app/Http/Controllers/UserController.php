<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(
        private UserService $userService
    ) {}

    public function me(Request $request): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();

            return $this->responseWithData('成功取得使用者資料', $user);
        } catch (\Exception $e) {
            return $this->error('取得使用者資料失敗: '.$e->getMessage());
        }
    }

    public function update(UpdateProfileRequest $updateProfileRequest): JsonResponse
    {
        try {
            $validatedData = $updateProfileRequest->validated();

            $user = $this->userService->updateProfile($validatedData);

            return $this->responseWithData('成功更新使用者資料', $user);
        } catch (\Exception $e) {
            return $this->error('更新使用者資料失敗: '.$e->getMessage());
        }

    }
}
