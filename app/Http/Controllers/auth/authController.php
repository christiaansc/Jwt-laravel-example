<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class authController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['loginAdmin', 'register', 'logout', 'loginEcommerce']]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);

        try {

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'type_user' => 1,
                'password' => $request->password,
            ]);


            return response()->json([
                'message' => 'User Successfully created',
                'user' => $user,
            ], 201);
        } catch (Throwable $e) {

            // dd($e);
            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginAdmin(Request $request)
    {
        $credentials = $request->only(['email', 'password']);


        $validator = Validator::make($credentials, [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);

        try {

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if (!$token = auth('api')->attempt(['state' => '1', 'type_user' => '1' , 'password'=> $request->password , 'email' =>$request->email])) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $this->respondWithToken($token);
        } catch (JWTException $e) {
            return response()->json([
                'error' => $e
            ], 500);
        }
    }


    public function loginEcommerce(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $validator = Validator::make($credentials, [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);

        try {

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if (!$token = auth('api')->attempt(['state' => '1', 'type_user' => '2' , 'password'=> $request->password , 'email' =>$request->email])) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $this->respondWithToken($token);
        } catch (JWTException $e) {
            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        $user =   auth('api')->user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expire_in' => auth('api')->factory()->getTTL() * 60,
            'user' =>[
                'name'=>$user->name,
                'email'=>$user->email,

            ]
        ]);
    }
}
