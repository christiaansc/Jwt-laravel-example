<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Error;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     */


    /**
     * Display the specified resource.
     */
    public function getAlluser()
    {
        $users =  new UserCollection(User::all());

        if (!$users) throw new Error(message: 'Users', code: 401);

        return response()->json($users);
    }

    public function getUserById(string $id)
    {

        //code...
        $user = new UserCollection(User::findOne($id));
        if (!$user) return response()->json(['message' => 'User not found', 'statusCode' => 404], 404);
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
