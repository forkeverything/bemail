<?php

namespace Tests\Unit\Translation\Factories;

use App\Language;
use App\Translation\Factories\MessageFactory;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Message;
use App\Translation\RecipientType;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as FakerFactory;

class MessageFactoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Generate fake fields.
     *
     * @var \Faker\Generator
     */
    private $faker;
    /**
     * @var User
     */
    private $user;
    /**
     * @var MessageFactory
     */
    private $factory;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->faker = FakerFactory::create();
        $this->user = factory(User::class)->create();
        $this->factory = MessageFactory::newMessageFromUser($this->user);
    }


    /**
     * @test
     */
    public function it_instantiates_from_a_user()
    {$this->assertInstanceOf(MessageFactory::class, $this->factory);
        $this->assertEquals($this->user->email, $this->factory->senderEmail());
        $this->assertEquals($this->user->name, $this->factory->senderName());
        $this->assertEquals($this->user, $this->factory->owner());
    }

    /**
     * @test
     */
    public function it_instantiates_from_a_message_as_a_reply()
    {
        $message = factory(Message::class)->create();
        $factory = MessageFactory::newReplyToMessage($message);
        $this->assertInstanceOf(MessageFactory::class, $factory);
        $this->assertEquals($message->id, $factory->messageId());
        $this->assertEquals(1, $factory->autoTranslateReply());
        $this->assertEquals(0, $factory->sendToSelf());
        $this->assertEquals($message->owner, $factory->owner());
        $this->assertEquals($message->lang_tgt_id, $factory->langSrcId());
        $this->assertEquals($message->lang_src_id, $factory->langTgtId());
    }

    /**
     * @test
     */
    public function it_has_sender_email()
    {
        $email = 'foo@bar.com';
        $this->factory->senderEmail($email);
        $this->assertEquals($email, $this->factory->senderEmail());
    }

    /**
     * @test
     */
    public function it_has_sender_name()
    {
        $name = 'John Dough';
        $this->factory->senderName($name);
        $this->assertEquals($name, $this->factory->senderName());
    }

    /**
     * @test
     */
    public function it_has_subject()
    {
        $subject = 'Super Importamt';
        $this->factory->subject($subject);
        $this->assertEquals($subject, $this->factory->subject());
    }

    /**
     * @test
     */
    public function it_has_a_body()
    {
        $body = 'This is the message body.';
        $this->factory->body($body);
        $this->assertEquals($body, $this->factory->body());
    }

    /**
     * @test
     */
    public function it_has_auto_translate_reply_option()
    {
        $autoTranslateReply = false;
        $this->factory->autoTranslateReply($autoTranslateReply);
        $this->assertEquals($autoTranslateReply, $this->factory->autoTranslateReply());
    }

    /**
     * @test
     */
    public function it_has_send_to_self_option()
    {
        $sendToSelf = true;
        $this->factory->sendToSelf($sendToSelf);
        $this->assertEquals($sendToSelf, $this->factory->sendToSelf());
    }

    /**
     * @test
     */
    public function it_has_source_language_id()
    {
        $langSrcId = 1;
        $this->factory->langSrcId($langSrcId);
        $this->assertEquals($langSrcId, $this->factory->langSrcId());
    }

    /**
     * @test
     */
    public function it_has_target_language_id()
    {
        $langTgtId = 1;
        $this->factory->langTgtId($langTgtId);
        $this->assertEquals($langTgtId, $this->factory->langTgtId());
    }

    /**
     * @test
     */
    public function it_has_an_owner()
    {
        $owner = factory(User::class)->create();
        $this->factory->owner($owner);
        $this->assertEquals($owner, $this->factory->owner());
    }

    /**
     * @test
     */
    public function it_has_a_message_id()
    {
        $messageId = 1;
        $this->factory->messageId($messageId);
        $this->assertEquals($messageId, $this->factory->messageId());
    }


    /**
     * @test
     */
    public function it_creates_a_message()
    {
        $message = $this->factory->subject($this->faker->sentence)
                      ->body($this->faker->paragraph)
                      ->autoTranslateReply(true)
                      ->sendToSelf(false)
                      ->langSrcId(Language::english()->id)
                      ->langTgtId(Language::japanese()->id)
                      ->make();
        $this->assertInstanceOf(Message::class, $message);
    }

}


