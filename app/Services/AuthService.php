<?php

namespace App\Services;

use App\Mails\QueuedVerifyEmail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

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

            Event::dispatch(new Registered($user));

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

        // 密碼錯誤或登入失敗
        if (! $token) {
            throw new \Exception('帳號或密碼錯誤');
        }

        return $token;
    }

    public function findUserById(int $id): User
    {
        return User::findOrFail($id);
    }
}
