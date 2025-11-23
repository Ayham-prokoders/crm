<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Notifications\AssignedToClassNotification;

class AssignedToClassNotificationTest extends TestCase
{
    /**
     * Test notification sends data to the server.
     *
     * @return void
     */
    public function testNotificationSendsDataToServer()
    {
        // Mock the HTTP facade to expect a post request to the server
        Http::fake([
            'https://prokoders.click/api/send-event' => Http::response(['message' => 'Event dispatched successfully'], 200),
        ]);

        // Create a user and trigger the notification
        $user = User::factory()->create();

        // Assign admin role to admin user
        $adminRole = Role::where('name', 'admin')->first();
        $user->assignRole($adminRole);
        $user->update([
            'current_role_id' => $adminRole->id,
            'role' => $adminRole->id
        ]);

        // Notify the user with the notification
        $user->notify(new AssignedToClassNotification($user->id));

        // Assert that the post request was sent to the server with the expected data
        Http::assertSent(function ($request) use ($user) {
            return
                $request->url() === 'https://prokoders.click/api/send-event' &&
                $request['notification']['title'] === __('message.class_assign') &&
                $request['notification']['user_id'] === $user->id;
        });
    }

}


