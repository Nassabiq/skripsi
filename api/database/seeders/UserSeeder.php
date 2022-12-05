<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $role = ['Manager Inventory', 'Manager Penjualan', 'Manager IT', 'Operator Inventory', 'Operator Produksi', 'Operator Finishing', 'Marketing', 'Pelanggan'];
        $role = Role::get();

        foreach ($role as $data) {
            $user = User::create([
                'id_user' => IdGenerator::generate(['table' => 'users', 'field' => 'id_user', 'length' => 10, 'prefix' => 'user-']),
                'id_role' => $data->id_role,
                'nama_user' => $data->nama_role,
                'email' => Str::slug($data->nama_role) . '@app.com',
                'password' => bcrypt('user1234'),
                'tgl_register' => Carbon::now(),
            ]);
        }
    }
}
