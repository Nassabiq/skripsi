<?php

namespace Database\Seeders;

use App\Models\Role;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role = ['Manager Inventory', 'Manager Penjualan', 'Admin', 'Operator Inventory', 'Operator Produksi', 'Operator Finishing', 'Marketing', 'Pelanggan'];

        foreach ($role as $data) {
            Role::create([
                'id_role' => IdGenerator::generate(['table' => 'role', 'field' => 'id_role', 'length' => 7, 'prefix' => 'role-']),
                'nama_role' => $data
            ]);
        }
    }
}
