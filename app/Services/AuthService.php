<?php

namespace App\Services;

use App\Mails\QueuedVerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function register(array $data)
    {
        try {
            // 建立新的使用者
            $user = new User;
            $user->name = $data['name'];
            $user->gender = $data['gender'];
            $user->birth_date = $data['birth_date'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save();

            // 寄送驗證信
            $user->notify(new QueuedVerifyEmail);

            return $user;

        } catch (\Exception $e) {
            throw $e;
        }

    }

    public function login(array $validatedData)
    {
        // 透過 email 搜尋使用者
        $user = User::where('email', $validatedData['email'])->first();

        if (! $user) {
            throw new \Exception('查無該使用者');
        }

        if (! $user->hasVerifiedEmail()) {
            throw new \Exception('請先驗證信箱');
        }

        // 取得 token
        $token = Auth::attempt($validatedData);

        return $token;
    }

    public function findUserById(int $id): User
    {
        return User::findOrFail($id);
    }
}
