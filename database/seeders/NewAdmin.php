<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NewAdmin extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'eng.alimoh98m@gmail.com',
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
