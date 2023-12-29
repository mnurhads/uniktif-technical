<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
                [ 
                    'email'    => $request->email,
                    'password' => bcrypt($request->password)
                ]
        ));

        return response()->json([
            'responseCode' => 200,
            'responseMsg' => 'Registrasi Akun Sukses',
            'user' => $user
        ], 200);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (!$token = JWTAuth::attempt($validator->validated())) {
            return response()->json([
                'responseCode' => 422,
                'responseMsg' => 'Invalid email and password.',
            ], 422);
        }

        return $this->createNewToken($token);
    }

    public function refresh() {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return $this->createNewToken(auth()->refresh());
    }

    public function user() {
        return response()->json(auth()->user());
    }

    public function logout() {
        auth()->logout();
        return response()->json([
            'responseCode' => 200,
            'responseMsg' => 'Berhasil Logout'
        ], 200);
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
