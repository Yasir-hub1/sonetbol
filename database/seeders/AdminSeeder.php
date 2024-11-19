<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     public function run()
     {
         // Crear rol de admin si no existe
         $adminRole = Role::firstOrCreate(['name' => 'admin']);

         // Crear usuario admin
         User::firstOrCreate(
             ['email' => 'admin@example.com'],
             [
                 'name' => 'Administrador',
                 'password' => bcrypt('admin123'),
                 'role_id' => $adminRole->id,
                 'email_verified_at' => now(),
             ]
         );
     }

}
