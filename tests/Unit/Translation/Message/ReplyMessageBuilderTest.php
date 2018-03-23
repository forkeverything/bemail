<?php

namespace Tests\Unit\Translation\Message;

use App\InboundMail\Postmark\PostmarkInboundMailRecipient;
use App\InboundMail\Postmark\PostmarkInboundMailRequest;
use App\Translation\Factories\AttachmentFactory;
use App\Translation\Factories\MessageFactory;
use App\Translation\Factories\RecipientFactory;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Message;
use App\Translation\Message\ReplyMessageBuilder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery as m;

class ReplyMessageBuilderTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @var m\Mock
     */
    private $request;
    /**
     * @var m\Mock
     */
    private $originalMessage;
    /**
     * @var ReplyMessageBuilder
     */
    private $builder;

    protected function setUp()
    {
        parent::setUp();
        $this->request = m::mock(PostmarkInboundMailRequest::class);
        $this->originalMessage = m::mock(Message::class);
        $this->builder = new ReplyMessageBuilder($this->request, $this->originalMessage);
    }

    /**
     * @test
     */
    public function it_instantiates_from_inbound_mail_request_and_original_message()
    {
        $this->assertInstanceOf(ReplyMessageBuilder::class, $this->builder);
    }

    /**
     * @test
     * @param null $fakeMessage
     */
    public function it_builds_message_model($fakeMessage = null)
    {

        if (is_null($fakeMessage)) {
            $fakeMessage = m::mock(Message::class);
        }

        $this->request->shouldReceive('fromAddress');
        $this->request->shouldReceive('fromName');
        $this->request->shouldReceive('subject');
        $this->request->shouldReceive('strippedReplyBody');

        $factory = m::mock(MessageFactory::class);
        $factory->shouldReceive('senderEmail')->andReturnSelf();
        $factory->shouldReceive('senderName')->andReturnSelf();
        $factory->shouldReceive('subject')->andReturnSelf();
        $factory->shouldReceive('body')->andReturnSelf();
        $factory->shouldReceive('make')->andReturn($fakeMessage);

        $this->originalMessage->shouldReceive('newReply')->andReturn($factory);

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

        $recipient = m::mock(PostmarkInboundMailRecipient::class);
        $recipient->shouldReceive('email')->andReturn('mike@example.com');

        $this->request->shouldReceive('standardRecipients')->andReturn([$recipient]);
        $this->request->shouldReceive('ccRecipients')->andReturn([$recipient]);
        $this->request->shouldReceive('bccRecipients')->andReturn([$recipient]);

        $this->originalMessage->shouldReceive('getAttribute')
                                ->with('sender_email')
                                ->andReturn('mike@example.com');

        $this->it_builds_message_model($message);

        $this->builder->buildRecipients();

    }

    /**
     * @test
     * @expectedException \Exception
     * @expectedExceptionMessage Something went wrong.
     */
    public function it_deletes_message_when_failing_to_build_recipients()
    {
        $message = factory(Message::class)->create();
        $this->assertDatabaseHas('messages', ['id' => $message->id]);
        $this->it_builds_message_model($message);
        $this->request->shouldReceive('standardRecipients')
                      ->andThrowExceptions([new \Exception("Something went wrong.")]);
        $this->builder->buildRecipients();
        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_builds_attachments()
    {
        $attachmentFactory = m::mock(AttachmentFactory::class);

        $attachmentFactory->shouldReceive('attachmentFiles')->andReturnSelf();
        $attachmentFactory->shouldReceive('make');


        $message = m::mock(Message::class);
        $message->shouldReceive('newAttachments')
                ->andReturn($attachmentFactory);


        $this->request->shouldReceive('attachments');

        $this->it_builds_message_model($message);

        $this->builder->buildAttachments();
    }

    /**
     * @test
     * @expectedException \Exception
     * @expectedExceptionMessage Something went wrong.
     */
    public function it_deletes_message_when_failing_to_build_attachments()
    {
        $message = factory(Message::class)->create();
        $this->assertDatabaseHas('messages', ['id' => $message->id]);
        $this->it_builds_message_model($message);


        $this->request->shouldReceive('attachments')
                      ->andThrowExceptions([new \Exception("Something went wrong.")]);

        $this->builder->buildAttachments();

        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
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
