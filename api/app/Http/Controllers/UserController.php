<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        $role = '%' . $request->role . '%';

        $user = $request->show == null
            ? User::with('role')->get()
            : User::with('role')->where('id_role', 'like', $role)->where('nama_user', 'like', $search)->paginate($request->show);

        return response()->json($user, 200);
    }

    public function role()
    {
        $role = Role::get();
        return response()->json($role, 200);
    }

    public $rules = [
        'nama_user' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'role' => 'required',
    ];

    public function addUser(Request $request)
    {
        $validator =  Validator::make($request->all(), $this->rules);
        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $id_user = IdGenerator::generate(['table' => 'users', 'field' => 'id_user', 'length' => 10, 'prefix' => 'user-']);
        $user = User::create([
            'id_user' => $id_user,
            'id_role' => $request->role,
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'tgl_register' => Carbon::now(),
            'password' => Hash::make('user1234')
        ]);
        return response()->json($user, 200);
    }

    public function updateUser(Request $request, $id)
    {
        $validator =  Validator::make($request->all(), [
            'nama_user' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'role' => 'required',
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $user = User::findOrFail($id);
        $user->id_role = $request->role;
        $user->nama_user = $request->nama_user;
        $user->email = $request->email;

        $user->save();
        return response()->json($user, 200);
    }
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json($user, 200);
    }
}
