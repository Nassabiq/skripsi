<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

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
        $role = ['Manager Inventory', 'Manager Penjualan', 'Manager IT', 'Operator Inventory', 'Operator Produksi', 'Operator Finishing', 'Marketing', 'Pelanggan'];

        foreach ($role as $data) {
            Role::create([
                'name' => $data,
                'guard_name' => 'api'
            ]);
        }
    }
}
