<?php
use App\Events\UserAuthenticated;
use App\Listeners\ProcessUserAuthentication;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ProcessUserAuthenticationTest extends TestCase
{
    public function test_user_authenticated_event_is_handled()
    {
        // Arrange
        $userId = 123;
        Event::fake();

        // Act
        Event::dispatch(new UserAuthenticated($userId));

        // Assert
        Event::assertDispatched(UserAuthenticated::class, function ($event) use ($userId) {
            return $event->userId === $userId;
        });

        // Assert that the cache was updated
        $cacheKey = "user_auth_status_{$userId}";
        $this->assertTrue(Cache::has($cacheKey));
    }
}
