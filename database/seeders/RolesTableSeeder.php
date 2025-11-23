<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('roles')->delete();

        \DB::table('roles')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'admin',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-09 09:40:42',
                'updated_at' => '2024-09-09 09:40:42',
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'supervisor',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-09 09:40:42',
                'updated_at' => '2024-09-09 09:40:42',
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'trainee',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-09 09:40:42',
                'updated_at' => '2024-09-09 09:40:42',
            ),
            3 =>
            array (
                'id' => 4,
                'name' => 'trainer',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-09 09:40:42',
                'updated_at' => '2024-09-09 09:40:42',
            ),
            4 =>
            array (
                'id' => 5,
                'name' => 'companySupervisor',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-09 09:40:42',
                'updated_at' => '2024-09-09 09:40:42',
            ),
            5 =>
            array (
                'id' => 6,
                'name' => 'pre_trainer',
                'guard_name' => 'sanctum',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 =>
            array (
                'id' => 7,
                'name' => 'viewer',
                'guard_name' => 'sanctum',
                'created_at' => '2025-05-01 06:01:49',
                'updated_at' => '2025-05-01 06:01:49',
            ),
            7 =>
            array (
                'id' => 8,
                'name' => 'accountant',
                'guard_name' => 'sanctum',
                'created_at' => '2025-05-01 06:01:49',
                'updated_at' => '2025-05-01 06:01:49',
            ),
            8 =>
            array (
                'id' => 9,
                'name' => 'coordinator',
                'guard_name' => 'sanctum',
                'created_at' => '2025-05-01 06:01:49',
                'updated_at' => '2025-05-01 06:01:49',
            ),
            9 =>
            array(
                'id' => 10,
                'name' => 'accreditation_manager',
                'guard_name' => 'sanctum',
                'created_at' => '2025-05-01 06:01:49',
                'updated_at' => '2025-05-01 06:01:49',
            ),
        ));


    }
}
