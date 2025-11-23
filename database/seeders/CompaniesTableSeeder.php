<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('companies')->delete();
        
        \DB::table('companies')->insert(array (
            0 => 
            array (
                'id' => 2,
                'lang_code' => 'en',
                'name' => 'Fly Qatar',
                'address' => 'Qatar',
                'created_at' => '2025-01-09 16:49:41',
                'updated_at' => '2025-01-09 16:49:41',
            ),
            1 => 
            array (
                'id' => 3,
                'lang_code' => 'en',
                'name' => 'Prokoders Test',
                'address' => 'Dubai',
                'created_at' => '2025-01-09 23:17:50',
                'updated_at' => '2025-01-09 23:17:50',
            ),
            2 => 
            array (
                'id' => 4,
                'lang_code' => 'en',
                'name' => 'LMA',
                'address' => 'Dubai',
                'created_at' => '2025-01-11 17:09:23',
                'updated_at' => '2025-01-11 17:09:23',
            ),
        ));
        
        
    }
}