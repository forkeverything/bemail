<?php

namespace Tests\Unit;

use App\Translation\Message;
use App\Translation\Recipient;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipientTest extends TestCase
{

    use DatabaseTransactions;

    private static $recipient;

    /**
     * Set / run these before each test
     */
    public function setUp()
    {
        parent::setUp();
        static::$recipient = factory(Recipient::class)->create();
    }

    /**
     * @test
     */
    public function it_should_be_able_to_mass_assign_these_fields()
    {
        $fields = [
            'email' => 'sam@recipient.com',
            'user_id' => factory(User::class)->create()->id
        ];

        $recipient = Recipient::create($fields);

        foreach ($fields as $key => $value) {
            $this->assertEquals($recipient->{$key}, $value);
        }

    }

    /**
     * @test
     */
    public function it_fetches_messages_sent_to_recipient()
    {
        $this->assertCount(0, static::$recipient->messages);
        $messageIds = factory(Message::class, 3)->create()->pluck('id')->toArray();
        static::$recipient->messages()->sync($messageIds);
        $this->assertCount(3, static::$recipient->fresh()->messages);
    }
    
    /**
     * @test
     */
    public function it_fetches_the_user_that_sends_messages_to_this_recipient()
    {
        $this->assertInstanceOf('App\User', static::$recipient->sender);
    }

    /**
     * @test
     */
    public function it_finds_recipients_for_given_user()
    {
        $user = factory(User::class)->create();
        $this->assertEquals($user->recipients->count(), 0);
        $recipients = factory(Recipient::class, 3)->create([
            'user_id' => $user->id
        ]);
        $this->assertEquals(Recipient::belongingTo($user)->get()->count(), 3);
    }
}
