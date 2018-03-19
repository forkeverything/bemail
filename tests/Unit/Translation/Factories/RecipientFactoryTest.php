<?php

namespace Tests\Unit\Translation\Factories;

use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Factories\RecipientFactory;
use App\Translation\Message;
use App\Translation\RecipientType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class RecipientFactoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var RecipientFactory
     */
    protected $factory;

    public function setUp()
    {
        parent::setUp();
        $this->message = factory(Message::class)->create();
        $this->factory = new RecipientFactory($this->message);
    }

    /**
     * @test
     */
    public function it_instantiates_using_a_message()
    {
        $this->assertInstanceOf(RecipientFactory::class, new RecipientFactory($this->message));
    }

    /**
     * @test
     */
    public function it_has_recipient_emails()
    {
        $emails = [
            'foo@example.com',
            'bar@example.com',
            'baz@example.com'
        ];
        $recipientEmails = RecipientEmails::new()->addListOfStandardEmails(implode(',', $emails));
        $this->assertNull($this->factory->recipientEmails());
        $this->factory->recipientEmails($recipientEmails);

        $this->assertInstanceOf(RecipientEmails::class, $this->factory->recipientEmails());

        foreach ($emails as $index => $email) {
            $this->assertEquals($email, $this->factory->recipientEmails()->standard()[$index]);
        }
    }

    /**
     * @test
     */
    public function it_creates_one_recipient()
    {
        $email = 'mike@example.com';
        $this->assertCount(0, $this->message->recipients);
        $recipientEmails = RecipientEmails::new()->addEmailToType($email, RecipientType::cc());
        $this->factory->recipientEmails($recipientEmails)
                      ->make();
        $this->assertCount(1, $this->message->fresh()->recipients);
        $this->assertEquals($email, $this->message->fresh()->recipients->first()->email);
    }

}
