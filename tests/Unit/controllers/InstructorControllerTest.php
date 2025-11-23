<?php
namespace Tests\Unit\controllers;

use Tests\TestCase;
use App\Models\User;
use Modules\Lms\Models\Classe;
use Modules\TrainerManagement\Models\Instructor;

class InstructorControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        Instructor::factory()->count(3)->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/instructors/all');
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $this->user->current_role_id = 4;
        $this->user->save();

        $data = [
            "location" => "New York",
            "field" => "Computer Science",
            "work_history" => "10 years in various educational institutions",
            "category_id" => 1,
            "professional_summary" => "Experienced instructor with a focus on technology and innovation.",

            "experience" => [
                [
                    "job_title" => "Senior Instructor",
                    "organization" => "Tech Academy",
                    "start_date" => "2015-08-01",
                    "responsibilities" => [
                        "Teaching advanced programming",
                        "Curriculum development",
                        "Mentoring students"
                    ]
                ]
            ],

            "qualification" => [
                [
                    "degree" => "PhD in Computer Science",
                    "institution" => "MIT",
                    "graduation_date" => "2014-05-15",
                    "major" => "Artificial Intelligence"
                ]
            ],

            "certification" => [
                [
                    "title" => "Certified Java Developer",
                    "organization" => "Oracle",
                    "date" => "2020-09-10",
                    "validity" => "2023-09-10",
                    "file" => "cert_java_dev.pdf"
                ]
            ],

            "course_experience_lpc" => [
                [
                    "title" => "Advanced Java",
                    "subject" => "Programming",
                    "audience" => "Undergraduate students",
                    "duration" => "3 months",
                    "mode_of_delivery" => "Online",
                    "geographic_location" => "USA",
                    "number_of_session" => "12"
                ]
            ],

            "specilized_topics" => ["Artificial Intelligence", "Machine Learning", "Data Science"],

            "languages" => [
                [
                    "title" => "English",
                    "level" => "Advanced"
                ],
                [
                    "title" => "Spanish",
                    "level" => "Intermediate"
                ]
            ],

            "awards" => [
                [
                    "title" => "Best Teacher Award",
                    "body" => "National Teachers Association",
                    "description" => "Awarded for excellence in teaching",
                    "date" => "2021-11-30",
                    "file" => "award_certificate.pdf"
                ]
            ],

            "testimonials" => [
                [
                    "name" => "John Doe",
                    "position" => "Student",
                    "organization" => "Tech Academy",
                    "contact_info" => "john@example.com",
                    "content" => "Great instructor, highly recommended.",
                    "video_link" => "https://example.com/testimonial-video"
                ]
            ],

            "social_media_links" => [
                [
                    "title" => "LinkedIn",
                    "link" => "https://linkedin.com/in/instructor"
                ]
            ],

            "portofolio_url" => "https://portfolio.instructor.com",
            "linkedln_url" => "https://linkedin.com/in/instructor",

            "training_modes" => "Online, In-person",
            "availability" => "Weekdays",
            "country_availability" => "USA, UK",

            "engagement" => [
                [
                    "name" => "AI Conference",
                    "date" => "2023-07-15",
                    "topic" => "Future of AI in Education"
                ]
            ],

            "publication" => [
                [
                    "title" => "AI in Education",
                    "date" => "2022-05-20",
                    "publisher" => "Tech Publishers",
                    "link" => "https://techpublishers.com/ai-education"
                ]
            ]
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                        ->postJson('/api/instructors/create', $data);
        $response->assertStatus(201);
    }

    public function testGenerateCV()
    {
        $user = User::factory()->create();
        $instructor = Instructor::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
                        ->postJson('/api/instructors/' . $user->id . '/generate-cv');

        $response->assertStatus(200);
    }

    public function testAddRating()
{
    $user1 = User::factory()->create();
    $instructor=Instructor::factory()->create(['user_id'=>$user1->id]);
    // Simulate the request to add a rating
    $response = $this->actingAs($this->user, 'sanctum')
                     ->postJson('/api/instructors/add-rating', [
                         'instructor_id' => $user1->id,
                         'rating' => 5
                     ]);

    // Check the response status
    $response->assertStatus(200);
}

public function testSetInstructor()
{
    $admin = User::factory()->create(['current_role_id' => 1]);
    $instructor = User::factory()->create();
    $class = Classe::factory()->create();

    // Simulate the request to set the instructor
    $response = $this->actingAs($admin, 'sanctum')
                     ->postJson('/api/set-instructor', [
                         'instructor_id' => $instructor->id,
                         'class_id' => $class->id
                     ]);

    // Check the response status
    $response->assertStatus(200);
                    }
    // public function testDestroy()
    // {
    //     $item = Instructor::factory()->create();
    //     $response = $this->actingAs($this->user, 'sanctum')
    //                      ->deleteJson("api/instructors/{$item->id}");
    //     $response->assertStatus(200);
    // }



    public function test_get_instructor_profile_with_valid_slug()
    {
        // Create a user and an instructor
        $user = User::factory()->create();
        $instructor = Instructor::factory()->create([
            'user_id' => $user->id,
            'slug' => 'valid-slug' . uniqid(),
        ]);

        // Send request to the profile route using the route name
        $response = $this->get(route('instructor.profile', ['slug' => $instructor->slug]));

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert the view is correct
        $response->assertViewIs('profile.instructor-profile');

        // Assert the instructor data is passed to the view
        $response->assertViewHas('instructor', $instructor);
        $response->assertViewHas('user', $user);
    }



}
