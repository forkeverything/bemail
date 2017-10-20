<?php

namespace Tests\Unit\Translation\Factories;

use App\Translation\Factories\RecipientFactory;
use App\Translation\Message;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class RecipientFactoryTest extends TestCase
{
    use DatabaseTransactions;

    private static $message;

    public function setUp()
    {
        // Remember to call parent::setUp(). One potential error if you don't is
        // factory undefined for [default] error.
        parent::setUp();

        static::$message = factory(Message::class)->create();
    }

   /**
    * Assert Recipient IS created for the Message('s) sender. We aren't
    * testing whether Recipient is attached - that's handled in the
    * message factory.
    *
    * @test
    */
   public function it_make_a_recipient_for_given_message_and_new_email()
   {
       $this->assertCount(0, static::$message->sender->recipients);
       $email = 'somebody@example.com';
       (new RecipientFactory(static::$message, $email))->make();
       $this->assertCount(1, static::$message->sender->fresh()->recipients);
   }
   
   /**
    * @test
    */
   public function it_returns_existing_recipient_for_user()
   {
       $email = 'somebody@example.com';
       (new RecipientFactory(static::$message, $email))->make();
       $this->assertCount(1, static::$message->sender->recipients);
       (new RecipientFactory(static::$message, $email))->make();
       $this->assertCount(1, static::$message->sender->fresh()->recipients);
   }
}
