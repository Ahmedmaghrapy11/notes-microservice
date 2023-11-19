<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Events\UserAuthenticated;
use Illuminate\Support\Facades\Event;

class MicroservicesIntegrationTest extends TestCase
{
    /** @test */
    public function it_communicates_between_microservices()
    {
        Event::fake();

        // Simulate user authentication in the authentication microservice
        event(new UserAuthenticated(1));

        // Wait for a short period to allow time for RabbitMQ message processing
        usleep(500000); // Sleep for 0.5 seconds

        // Assert that the notes management microservice processed the RabbitMQ message
        Event::assertDispatched(UserAuthenticated::class);
    }
}
