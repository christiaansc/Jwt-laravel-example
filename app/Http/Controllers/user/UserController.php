<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\services\user\UserService;
use Error;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {

        $this->userService = $userService;

    }
    /**
     * Display a listing of the resource.
     */


    /**
     * Display the specified resource.
     */
    public function getAlluser()
    {

        $users = $this->userService->getAllUsers();
        return response()->json($users);
    }

    public function getUserById(string $id)
    {

        //code...
        $user  = $this->userService->getUserById($id);
        return response()->json(['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
