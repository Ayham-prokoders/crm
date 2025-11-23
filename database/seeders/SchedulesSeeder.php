<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SchedulesSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('schedules')->delete();
        
        \DB::table('schedules')->insert(array (
            0 => 
            array (
                'id' => 1,
                'course_id' => 129,
                'start_date' => '2025-04-07',
                'city_id' => 5,
                'trainer_id' => NULL,
                'online' => 0,
                'created_at' => '2025-01-09 13:39:34',
                'updated_at' => '2025-01-09 13:39:34',
            ),
            1 => 
            array (
                'id' => 2,
                'course_id' => 129,
                'start_date' => '2025-07-07',
                'city_id' => 39,
                'trainer_id' => NULL,
                'online' => 0,
                'created_at' => '2025-01-09 13:39:34',
                'updated_at' => '2025-01-09 13:39:34',
            ),
            2 => 
            array (
                'id' => 3,
                'course_id' => 129,
                'start_date' => '2025-10-06',
                'city_id' => 39,
                'trainer_id' => NULL,
                'online' => 0,
                'created_at' => '2025-01-09 13:39:34',
                'updated_at' => '2025-01-09 13:39:34',
            ),
            3 => 
            array (
                'id' => 4,
                'course_id' => 129,
                'start_date' => '2025-01-06',
                'city_id' => 3,
                'trainer_id' => NULL,
                'online' => 0,
                'created_at' => '2025-01-09 13:39:34',
                'updated_at' => '2025-01-09 13:39:34',
            ),
            4 => 
            array (
                'id' => 5,
                'course_id' => 129,
                'start_date' => '2025-04-07',
                'city_id' => 3,
                'trainer_id' => NULL,
                'online' => 0,
                'created_at' => '2025-01-09 13:39:34',
                'updated_at' => '2025-01-09 13:39:34',
            ),
            5 => 
            array (
                'id' => 6,
                'course_id' => 129,
                'start_date' => '2025-07-07',
                'city_id' => 3,
                'trainer_id' => NULL,
                'online' => 0,
                'created_at' => '2025-01-09 13:39:34',
                'updated_at' => '2025-01-09 13:39:34',
            ),
        ));
        
        
    }
}