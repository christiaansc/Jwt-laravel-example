<?php

namespace App\Http\Controllers\auth;

use App\Dto\AuthDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\auth\RegisterRequest;
use App\services\Auth\AuthServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class authController extends Controller
{
    private AuthServices $service;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthServices $service)
    {
        $this->middleware('auth:api', ['except' => ['loginAdmin', 'register', 'logout', 'loginEcommerce']]);
        $this->service = $service;
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    public function register(RegisterRequest $request)
    {
        $userDto = new AuthDto(
            $request->name,
            $request->email,
            $request->password,
        );

        $user = $this->service->registerUser($userDto);

        return response()->json([
            'message' => 'User created',
            'user' => $user,
        ]);
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

            if (!$token = auth('api')->attempt(['state' => '1', 'type_user' => '1', 'password' => $request->password, 'email' => $request->email])) {
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

            if (!$token = auth('api')->attempt(['state' => '1', 'type_user' => '2', 'password' => $request->password, 'email' => $request->email])) {
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
            'user' => [
                'name' => $user->name,
                'email' => $user->email,

            ]
        ]);
    }
}
