<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ExampleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\CitiesTableSeeder;
use Database\Seeders\TopicsTableSeeder;
use Database\Seeders\CoursesTableSeeder;
use Database\Seeders\CompaniesTableSeeder;
use Database\Seeders\LocationsTableSeeder;
use Database\Seeders\SchedulesTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\ModelHasRolesTableSeeder;
use Database\Seeders\FooterSettingsTableSeeder;
use Database\Seeders\RegisterRequestsTableSeeder;
use Database\Seeders\RoleHasPermissionsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call(UserSeeder::class);
        // $this->call(PermissionSeeder::class);
        // $this->call(ExampleSeeder::class);
        // $this->call(FooterSettingsTableSeeder::class);
        // $this->call(RolesTableSeeder::class);
        // $this->call(PermissionsTableSeeder::class);
        // $this->call(RoleHasPermissionsTableSeeder::class);
        // $this->call(CompaniesTableSeeder::class);
        // $this->call(UsersTableSeeder::class);
        // $this->call(ModelHasRolesTableSeeder::class);
        // $this->call(TopicsTableSeeder::class);
        // $this->call(RegisterRequestsTableSeeder::class);
        $this->call(AccreditationManagerRoleSeeder::class);
    }
}
