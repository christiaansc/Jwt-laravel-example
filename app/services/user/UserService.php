<?php

namespace App\services\user;

use App\Http\Resources\UserCollection;
use App\Models\User;
use Exception;

class UserService
{

    public function getAllUsers()
    {

        try {
            $users =  new UserCollection(User::all());
            if (!$users) return response()->json(['Message' => 'Error en el servidor']);

            return $users;
        } catch (\Exception $e) {

            return response()->json([
                'Message' => 'Error en el servidor',
                'error' => $e,
            ]);
        }
    }


    public function getUserById(string $id)
    {

        try {

            $user =  User::find($id);
            if (!$user) return response()->json(['message' => 'User not found', 'statusCode' => 404], 404);

            return $user;
        } catch (\Exception $e) {

            dd($e);
        }
    }


}
