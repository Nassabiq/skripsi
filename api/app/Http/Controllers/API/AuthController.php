<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) return response()->json($validator->errors());
        $id_user = IdGenerator::generate(['table' => 'users', 'field' => 'id_user', 'length' => 10, 'prefix' => 'USER-']);

        $user = User::create([
            'id_user' => $id_user,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $user->assignRole('Pelanggan');

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) return response()->json(['message' => 'Unauthorized'], 401);

        $user =  User::where('email', $request['email'])->first();
        $token = auth()->login($user);

        return response()->json([
            'message' => 'Welcome ' . $user->name,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}
