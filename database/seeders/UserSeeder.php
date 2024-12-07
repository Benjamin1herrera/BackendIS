<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'rut' => '215688232',
            'name' => 'Luis',
            'lastname1' => 'Cardenas',
            'lastname2' => 'Pinones',
            'phone' => '+56932034875',
            'email' => 'a@a.com',
            'password' => '123',
            'role_id' => '1',
            'isEnable' => true
        ]);

        User::create([
            'rut' => '21426966K',
            'name' => 'Benjamin',
            'lastname1' => 'Soto',
            'lastname2' => 'Herrera',
            'phone' => '+56987456903',
            'email' => 'b@b.com',
            'password' => '123',
            'role_id' => '2',
            'isEnable' => true
        ]);

        User::create([
            'rut' => '203486952',
            'name' => 'Manuel',
            'lastname1' => 'Moreno',
            'lastname2' => 'Varas',
            'phone' => '+56985943456',
            'email' => 'c@c.com',
            'password' => '123',
            'role_id' => '3',
            'isEnable' => true
        ]);
    }
}
