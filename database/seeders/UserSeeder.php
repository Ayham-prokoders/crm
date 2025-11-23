<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define role names
        $roles = ['admin', 'supervisor', 'trainee', 'trainer','companySupervisor','pre_trainer'];

        // Create roles
        // foreach ($roles as $roleName) {
        //     Role::create(['name' => $roleName,'guard_name' => 'sanctum']);
        // }

        // // Assign all permissions to admin role
        // $adminRole = Role::where('name', 'admin')->first();
        // $adminRole->givePermissionTo(Permission::all());

        // Create admin user
        $admin = User::create([
            'id' => 1,
            'name' => 'admin',
            'email' => 'lpc@admin.com',
            'password' => bcrypt('password@lpc'),
        ]);

        // Assign admin role to admin user
        $adminRole = Role::where('name', 'admin')->first();
        $admin->assignRole($adminRole);
        $admin->update([
            'current_role_id'=>$adminRole->id,
            'role'=>$adminRole->id
        ]);

    }

}
