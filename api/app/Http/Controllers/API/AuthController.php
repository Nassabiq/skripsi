<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
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
            'nama_user' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $id_user = IdGenerator::generate(['table' => 'users', 'field' => 'id_user', 'length' => 10, 'prefix' => 'USER-']);

        $user = User::create([
            'id_user' => $id_user,
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'id_role' => 'role-08',
            'tgl_register' => Carbon::now(),
            'password' => Hash::make($request->password)
        ]);
        $token = auth()->login($user);
        // $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) return response()->json(['message' => 'Data yang dimasukkan salah'], 401);

        $user =  User::with('role')->where('email', $request['email'])->first();
        $token = auth()->login($user);

        return response()->json([
            'message' => 'Welcome ' . $user->name,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role->nama_role
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
