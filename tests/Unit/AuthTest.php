<?php
namespace Tests\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class AuthTest extends TestCase
{


    /** @test */
     /** @test */
     public function user_can_login_with_correct_credentials()
     {
         $plainPassword = 'password123';

         // Create a user with a known password
         $user = User::factory()->create([
             'password' => bcrypt($plainPassword),
         ]);

         // Attempt to log in with the correct credentials
         $response = $this->postJson('/api/login', [
             'email' => $user->email,
             'password' => $plainPassword,
         ]);

         // Assert that the login was successful
         $response->assertStatus(200);
     }


    /** @test */
    public function user_cannot_login_with_incorrect_credentials()
    {
        $response = $this->postJson('api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJson(['message' => __('message.credentials_incorrect')]);
    }
}
