<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompleteProfileRequest;
use App\Services\ProfileService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    use ApiResponse;

    public function __construct(private ProfileService $profileService) {}

    /**
     * 使用者補填第三方登入時缺少的個人資料（性別、生日、可修改名稱）
     */
    public function completeProfile(CompleteProfileRequest $completeProfileRequest): JsonResponse
    {
        $user = auth()->user();

        $this->profileService->updateProfile($user, $completeProfileRequest->validated());

        return $this->success('Profile completed successfully.');
    }
}
