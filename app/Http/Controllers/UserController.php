<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Enqueue\AmqpExt\AmqpConnectionFactory;
use Interop\Amqp\AmqpContext;
use Interop\Amqp\AmqpMessage;

class UserController extends Controller
{
    public function userAuthenticatedListener()
    {
        $connectionFactory = new AmqpConnectionFactory(getenv('RABBITMQ_DSN'));
        $context = $connectionFactory->createContext();

        $queue = $context->createQueue('user.authenticated');
        $consumer = $context->createConsumer($queue);

        while (true) {
            $message = $consumer->receive();

            // Process the message (e.g., update user-related data)
            $userData = json_decode($message->getBody(), true);
            $userId = $userData['user_id'];
            $token = $userData['token'];

            // Perform actions based on the received data

            $consumer->acknowledge($message);
        }
    }

}
