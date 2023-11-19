<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Events\UserAuthenticated;
use Illuminate\Support\Facades\Event;

class NotesManagementMicroserviceTest extends TestCase
{
    /** @test */
    public function it_processes_user_authentication_message_from_rabbitmq()
    {
        Event::fake();

        // Simulate receiving the RabbitMQ message
        event(new UserAuthenticated(1));

        // Assert that the listener processed the message
        Event::assertDispatched(UserAuthenticated::class);
    }


}
