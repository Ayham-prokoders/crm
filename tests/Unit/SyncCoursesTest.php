<?php
namespace Tests\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Console\Commands\SyncCourses;

class SyncCoursesTest extends TestCase
{

    /** @test */
    public function it_syncs_categories_and_courses_from_remote_api()
    {
        // Mock the CourseHelper to simulate API responses
        $mockedHelper = $this->mock(\App\Http\Helper\CourseHelper::class);
        $mockedHelper->shouldReceive('getCategories')->andReturn([
            'table_data' => [
            ],
            'next_step' => -1,
        ]);
        $mockedHelper->shouldReceive('getCourses')->andReturn([
            'table_data' => [
            ],
            'next_step' => -1,
        ]);

        // Run the sync command
        $this->artisan('app:sync-courses')
            ->expectsOutput('Courses and categories synchronized successfully.')
            ->assertExitCode(0);

    }


      /** @test */
      public function it_syncs_cities_and_locations_from_remote_api()
      {
          // Mock the CourseHelper to simulate API responses
          $mockedHelper = $this->mock(\App\Http\Helper\CityHelper::class);
          $mockedHelper->shouldReceive('getCities')->andReturn([
              'table_data' => [
              ],
              'next_step' => -1,
          ]);
          $mockedHelper->shouldReceive('getLocations')->andReturn([
              'table_data' => [
              ],
              'next_step' => -1,
          ]);

          // Run the sync command
          $this->artisan('app:sync-cities')
              ->expectsOutput('Cities and locations synchronized successfully.')
              ->assertExitCode(0);

      }
}
