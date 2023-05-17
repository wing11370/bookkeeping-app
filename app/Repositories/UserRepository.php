<?php

namespace App\Repositories;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function __construct(protected \App\Models\User $user)
    {
    }

    /**
     * @param array $array
     * @return User
     * @throws Exception
     */
    public function create(array $array): User
    {
        try {
            return $this->user->create([
                'name' => $array['name'],
                'email' => $array['email'],
                'password' => $array['password']
            ]);
        } catch (Exception $e) {
            throw new Exception('註冊失敗');
        }
    }
}
