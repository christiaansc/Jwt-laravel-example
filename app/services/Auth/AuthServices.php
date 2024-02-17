<?php


namespace App\services\Auth;

use App\Dto\AuthDto;
use App\Models\User;
use Error;
use Exception;

class AuthServices
{
    function registerUser(AuthDto $userDto): User
    {
        try {
            $user = User::create([
                'name' => $userDto->name,
                'email' => $userDto->email,
                'type_user' => 1,
                'password' => $userDto->password,
            ]);

            return $user;
        } catch (Exception $e) {

            throw new Error($e);
        }
    }
}
