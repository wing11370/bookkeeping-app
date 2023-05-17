<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(protected User $user, protected \App\Repositories\UserRepository $userRepository)
    {
    }

    /**
     * @param array $array
     * @return string
     * @throws Exception
     */
    public function register(array $array): string
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->create([
                'name' => $array['name'],
                'email' => $array['email'],
                'password' => Hash::make($array['password']),
            ]);
            DB::commit();
            return $this->getToken($user);
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('註冊失敗');
        }
    }

    /**
     * @param array $array
     * @return string
     * @throws Exception
     */
    public function login(array $array): string
    {
        try {
            $user = $this->user->where('email', $array['email'])->firstOrFail();
        } catch (Exception $e) {
            throw new Exception('查無此帳號');
        }
        if (!Hash::check($array['password'], $user->password)) {
            throw new Exception('密碼錯誤');
        }
        return $this->getToken($user);
    }

    private function getToken(User $user): string
    {
        return $user->createToken('token')->plainTextToken;
    }
}
