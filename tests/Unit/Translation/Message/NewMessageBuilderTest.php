<?php

namespace Tests\Unit\Translation\Message;

use App\Language;
use App\Translation\Factories\AttachmentFactory;
use App\Translation\Factories\MessageFactory;
use App\Translation\Factories\RecipientFactory;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Message;
use App\Translation\Message\NewMessageBuilder;
use App\Translation\Message\NewMessageFields;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery as m;

class NewMessageBuilderTest extends TestCase
{

    /**
     * @var m\Mock
     */
    private $fields;
    /**
     * @var NewMessageBuilder
     */
    private $builder;

    protected function setUp()
    {
        parent::setUp();
        $this->fields = m::mock(NewMessageFields::class);
        $this->builder = new NewMessageBuilder($this->fields);
    }

    /** @test */
    public function it_instantiates_from_new_message_fields()
    {
        $this->assertInstanceOf(NewMessageBuilder::class, $this->builder);
    }

    /** @test
     * @param m\Mock|null $fakeMessage
     */
    public function it_builds_message_model($fakeMessage = null)
    {

        // Other tests can pass in a Message and call this test
        // to build Message first.
        if (is_null($fakeMessage)) {
            $fakeMessage = m::mock(Message::class);
        }

        $this->fields->shouldReceive('subject');
        $this->fields->shouldReceive('body');
        $this->fields->shouldReceive('autoTranslateReply');
        $this->fields->shouldReceive('sendToSelf');
        $this->fields->shouldReceive('langSrcId');
        $this->fields->shouldReceive('langTgtId');

        $user = m::mock(User::class);

        $messageFactory = m::mock(MessageFactory::class);

        $messageFactory->shouldReceive('subject')->andReturnSelf();
        $messageFactory->shouldReceive('body')->andReturnSelf();
        $messageFactory->shouldReceive('autoTranslateReply')->andReturnSelf();
        $messageFactory->shouldReceive('sendToSelf')->andReturnSelf();
        $messageFactory->shouldReceive('langSrcId')->andReturnSelf();
        $messageFactory->shouldReceive('langTgtId')->andReturnSelf();
        $messageFactory->shouldReceive('make')->andReturn($fakeMessage);

        $user->shouldReceive('newMessage')
             ->once()
             ->andReturn($messageFactory);

        \Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        $this->builder->buildMessage();
    }

    /**
     * @test
     * @expectedException \Exception
     * @expectedExceptionMessage Must build Message model before Recipient(s).
     */
    public function it_raises_exception_when_building_recipients_without_message()
    {
        $this->builder->buildRecipients();
    }

    /**
     * @test
     */
    public function it_builds_recipients()
    {

        $recipientFactory = m::mock(RecipientFactory::class);
        $recipientFactory->shouldReceive('recipientEmails')
                         ->with(RecipientEmails::class)
                         ->andReturnSelf();

        $recipientFactory->shouldReceive('make');

        $message = m::mock(Message::class);
        $message->shouldReceive('newRecipients')->andReturn($recipientFactory);


        $this->fields->shouldReceive('recipients')->andReturn('john@example.com,sarah@example.com');

        $this->it_builds_message_model($message);

        $this->builder->buildRecipients();
    }

    /**
     * @test
     * @expectedException \Exception
     * @expectedExceptionMessage Must build Message model before Attachment(s).
     */
    public function it_raises_exception_when_building_attachment_without_message()
    {
        $this->builder->buildAttachments();
    }

    /**
     * @test
     */
    public function it_builds_attachments()
    {
        $attachmentFactory = m::mock(AttachmentFactory::class);

        $attachmentFactory->shouldReceive('attachmentFiles')->andReturnSelf();
        $attachmentFactory->shouldReceive('make');

        $message = m::mock(Message::class);
        $message->shouldReceive('newAttachments')->andReturn($attachmentFactory);


        $this->fields->shouldReceive('attachments')->andReturn([]);

        $this->it_builds_message_model($message);

        $this->builder->buildAttachments();
    }

    /**
     * @test
     */
    public function it_returns_built_message()
    {
        $subject = 'super awesome message';
        $message = new Message([
            'subject' => $subject
        ]);
        $this->it_builds_message_model($message);
        $this->assertEquals($subject, $this->builder->message()->subject);
    }

}
