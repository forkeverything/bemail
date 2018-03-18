<?php

namespace Tests\Unit\Translation\Factories;

use App\Http\Requests\CreateMessageRequest;
use App\Language;
use App\Translation\Attachments\FormUploadedFile;
use App\Translation\Factories\MessageFactory;
use App\Translation\Factories\MessageFactory\RecipientEmails;
use App\Translation\Message;
use App\Translation\RecipientType;
use App\Translation\Reply;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageFactoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_instantiates_from_a_user()
    {
        $user = factory(User::class)->create();
        $this->assertInstanceOf(MessageFactory::class, MessageFactory::newMessageFromUser($user));
    }

    // TODO ::: Test Message Attachments
}


