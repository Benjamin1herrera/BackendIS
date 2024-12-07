<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role as RoleModel;

class Role extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoleModel::create([
            'name' => 'Cliente'
        ]);

        RoleModel::create([
            'name' => 'Administrador'
        ]);

        RoleModel::create([
            'name' => 'Trabajador'
        ]);
    }
}
