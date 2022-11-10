<?php

namespace Database\Seeders;

use App\Models\User;
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
        $role = ['Manager Inventory', 'Manager Penjualan', 'Manager IT', 'Operator Inventory', 'Operator Produksi', 'Operator Finishing', 'Marketing', 'Pelanggan'];
        $id_user = IdGenerator::generate(['table' => 'users', 'field' => 'id_user', 'length' => 10, 'prefix' => 'USER-']);

        foreach ($role as $data) {
            $user = User::create([
                'id_user' => $id_user,
                'name' => $data,
                'email' => Str::slug($data) . '@app.com',
                'password' => bcrypt('user1234'),
            ]);
            $user->assignRole($data);
        }
    }
}
