<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('topics')->delete();
        
        \DB::table('topics')->insert(array (
            0 => 
            array (
                'id' => 11,
                'lang_code' => 'en',
                'title' => 'Accounting, Finance & Budgeting',
                'description' => NULL,
                'created_at' => '2025-01-09 18:03:04',
                'updated_at' => '2025-01-09 18:03:04',
            ),
            1 => 
            array (
                'id' => 12,
                'lang_code' => 'en',
                'title' => 'Human Resources',
                'description' => NULL,
                'created_at' => '2025-01-09 18:03:15',
                'updated_at' => '2025-01-09 18:03:15',
            ),
            2 => 
            array (
                'id' => 13,
                'lang_code' => 'en',
                'title' => 'Management & Leadership',
                'description' => NULL,
                'created_at' => '2025-01-09 18:03:21',
                'updated_at' => '2025-01-09 18:03:21',
            ),
            3 => 
            array (
                'id' => 14,
                'lang_code' => 'en',
                'title' => 'Project Management',
                'description' => NULL,
                'created_at' => '2025-01-09 18:03:27',
                'updated_at' => '2025-01-09 18:03:27',
            ),
            4 => 
            array (
                'id' => 15,
                'lang_code' => 'en',
                'title' => 'Administration & Secretary',
                'description' => NULL,
                'created_at' => '2025-01-09 18:03:32',
                'updated_at' => '2025-01-09 18:03:32',
            ),
            5 => 
            array (
                'id' => 16,
                'lang_code' => 'en',
                'title' => 'Quality & Productivity',
                'description' => NULL,
                'created_at' => '2025-01-09 18:03:37',
                'updated_at' => '2025-01-09 18:03:37',
            ),
            6 => 
            array (
                'id' => 17,
                'lang_code' => 'en',
                'title' => 'Customer Service',
                'description' => NULL,
                'created_at' => '2025-01-09 18:03:40',
                'updated_at' => '2025-01-09 18:03:40',
            ),
            7 => 
            array (
                'id' => 18,
                'lang_code' => 'en',
                'title' => 'IT',
                'description' => NULL,
                'created_at' => '2025-01-09 18:03:49',
                'updated_at' => '2025-01-09 18:03:49',
            ),
        ));
        
        
    }
}