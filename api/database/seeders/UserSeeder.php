<?php

namespace Database\Seeders;

use App\Models\User;
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

        foreach ($role as $data) {
            $user = User::create([
                'name' => $data,
                'email' => Str::slug($data) . '@app.com',
                'password' => bcrypt('user1234'),
            ]);
            $user->assignRole($data);
        }
    }
}
