<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear permisos
        $createLeadPermission = Permission::factory()->create([
            'name' => 'create-lead'
        ]);

        // Crear roles
        $managerRole = Role::factory()->create([
            'name' => 'manager'
        ]);

        // Asignar permiso al rol manager
        $managerRole->permissions()->attach($createLeadPermission->id);

        $agentRole = Role::factory()->create([
            'name' => 'agent'
        ]);

        // Crear usuarios manualmente
        $manager = User::factory()->create([
            'username' => 'manager_user',
            'password' => Hash::make('password123'),
            'last_login' => now(),
            'is_active' => true,
        ]);

        // Asignar roles a los usuarios
        $manager->roles()->attach($managerRole->id);
        $manager->roles()->attach($agentRole->id);


        $agent = User::factory()->create([
            'username' => 'agent_user',
            'password' => Hash::make('password123'),
            'last_login' => now(),
            'is_active' => true,
        ]);
        $agent->roles()->attach($agentRole->id);
    }
}
