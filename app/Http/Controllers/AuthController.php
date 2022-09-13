<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function register(Request $request)
    {
        try{
            $validated = Validator::make($request->only(['email','name','password']),[
                'name' => 'required|max:255|string',
                'email' => 'required|max:255|email:dns|unique:users,email',
                'password' => 'required|min:8'
            ]);

            if($validated->fails()){
                return response()->json([
                    'message' => $validated->errors(),
                    'status' => false
                ], Response::HTTP_UNAUTHORIZED);
            }

            $user = new User;

            $data = $user->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'message' => 'Register Berhasil!',
                'data' => $data
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo,
                'status' => false
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request)
    {
        try{
            $validated = Validator::make($request->only('email', 'password'), [
                'email' => 'required|exists:users,email',
                'password' => 'required'
            ]);

            if($validated->fails()){
                return response()->json([
                    'message' => $validated->errors(),
                    'status' => false
                ], Response::HTTP_UNAUTHORIZED);
            }

            $user = User::where('email', $request->email)->first();

            if(!Hash::check($request->password, $user->password)){
                return abort(401, 'Email atau Password tidak sesuai!');
            }

            $payload = [
                'iat' => intval(microtime(true)),
                'exp' => intval(microtime(true)) + (60 * 60 * 1000),
                'uid' => $user->id
            ];

            $token = JWT::encode($payload, env('JWT_SECRET'),'HS256');
            return response()->json([
                'access token' => $token
            ], Response::HTTP_OK);

        } catch (QueryException $e){
            return response()->json([
                'status' => false,
                'message' => 'Failed ' . $e->errorInfo
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
