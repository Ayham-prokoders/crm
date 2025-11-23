<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{User,Classe ,SessionCourse,Content,Attendance,Company};
use App\Models\Role;

class ExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trainer = Role::where('name', 'trainer')->first();
        $trainee = Role::where('name', 'trainee')->first();
        $companySupervisor=Role::where('name', 'companySupervisor')->first();
        $supervisor=Role::where('name', 'supervisor')->first();

        $trainer1 = User::create([
            'name' => 'trainer1',
            'email' => 'trainer1@t.com',
            'password' => bcrypt('password'),
        ]);

        $trainer1->assignRole('trainer');
        $trainer1->update(['current_role_id'=> $trainer->id,'role'=>$trainer->id]);
        $trainer2 = User::create([
            'name' => 'trainer2',
            'email' => 'trainer2@t.com',
            'password' => bcrypt('password'),
        ]);

        $trainer2->assignRole('trainer');
        $trainer2->update(['current_role_id'=>$trainer->id,'role'=>$trainer->id]);
        // $trainer2->assignRole('trainee');

        // $trainer2->update(['current_role_id'=>$trainee->id]);

        $company1=Company::create([
            'lang_code'=>'en',
            'name' => 'company 1',
            'address'=>'SAR'
        ]);
        $company2=Company::create([
            'lang_code'=>'en',
            'name' => 'company 2',
            'address'=>'SAR'
        ]);
        $trainee1 = User::create([
            'name' => 'trainee1',
            'email' => 'trainee1@t.com',
            'password' => bcrypt('password'),
            'last_name'=>'last_name',
            'company_id'=>1
        ]);

        $trainee1->assignRole('trainee');
        $trainee1->update(['current_role_id'=>$trainee->id,'role'=>$trainee->id]);

        $trainee2 = User::create([
            'name' => 'trainee2',
            'email' => 'trainee2@t.com',
            'password' => bcrypt('password'),
            'last_name'=>'last_name',
            'company_id'=>2
        ]);

        $trainee2->assignRole('trainee');
        $trainee2->update(['current_role_id'=>$trainee->id,'role'=>$trainee->id]);

        $companySupervisor1 = User::create([
            'name' => 'companySupervisor1',
            'email' => 'company1@supervisor.com',
            'password' => bcrypt('password'),
            'last_name'=>'last_name',
            'company_id'=>1
        ]);

        $companySupervisor1->assignRole('companySupervisor');
        $companySupervisor1->update(['current_role_id'=>$companySupervisor->id,'role'=>$companySupervisor->id]);


        $companySupervisor2 = User::create([
            'name' => 'companySupervisor2',
            'email' => 'company2@supervisor.com',
            'password' => bcrypt('password'),
            'last_name'=>'last_name',
            'company_id'=>2
        ]);

        $companySupervisor2->assignRole('companySupervisor');
        $companySupervisor2->update(['current_role_id'=>$companySupervisor->id,'role'=>$companySupervisor->id]);

    }
}
