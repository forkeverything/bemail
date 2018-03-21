<?php

namespace Tests\Unit;

use App\Language;
use App\Payment\Credit\CreditTransaction;
use App\Payment\Plan;
use App\Translation\Factories\MessageFactory;
use App\Translation\Message;
use App\Translation\Recipient;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @var User
     */
    private $user;

    /**
     * Set / run these before each test
     */
    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function it_is_be_able_to_mass_assign_these_fields()
    {
        $fields = [
            'name' => 'John',
            'email' => 'John@example.com',
            'password' => bcrypt('secret'),
            'credits' => 10,
            'language_id' => 1
        ];

        $user = User::create($fields);

        foreach ($fields as $key => $value) {
            $this->assertEquals($user->{$key}, $value);
        }
    }

    /**
     * @test
     */
    public function it_hides_these_fields()
    {
        $fields = array_keys($this->user->toArray());
        $this->assertNotContains('password', $fields);
        $this->assertNotContains('remember_token', $fields);
    }

    /**
     * @test
     */
    public function it_has_a_default_language()
    {
        $this->assertInstanceOf(Language::class, $this->user->defaultLanguage);
    }
    
    /**
     * @test
     */
    public function it_fetches_user_messages()
    {
        $this->assertCount(0, $this->user->messages);
        factory(Message::class, 3)->create([
            'user_id' => $this->user->id
        ]);

        $this->assertCount(3, $this->user->fresh()->messages);
    }

    /**
     * @test
     */
    public function it_fetches_recipients_for_messages_sent_by_user()
    {
        $message1 = factory(Message::class)->create([
            'user_id' => $this->user->id
        ]);
        factory(Recipient::class, 3)->create(['message_id' => $message1->id]);

        $message2 = factory(Message::class)->create([
            'user_id' => $this->user->id
        ]);
        factory(Recipient::class, 2)->create(['message_id' => $message2->id]);

        $this->assertCount(5, $this->user->recipients);

    }

    /**
     * @test
     */
    public function it_has_many_credit_transactions()
    {
        factory(CreditTransaction::class, 3)->create([
            'user_id' => $this->user->id
        ]);
        $this->assertCount(3, $this->user->fresh()->creditTransactions);
    }


    /**
     * @test
     */
    public function it_gets_the_users_plan()
    {
        $this->assertInstanceOf(Plan::class, $this->user->plan());
    }

    /**
     * @test
     */
    public function it_gets_the_amount_of_user_credits()
    {
        $this->user->update([
            'credits' => 10
        ]);
        $this->assertEquals(10, $this->user->credits());
    }

    /**
     * @test
     */
    public function it_sets_the_amount_of_user_credits()
    {
        $this->user->update([
            'credits' => 10
        ]);
        $this->user->credits($this->user->credits() - 5);
        $this->assertEquals(5, $this->user->credits);
        $this->user->credits($this->user->credits() + 15);
        $this->assertEquals(20, $this->user->credits);
    }

    /**
     * @test
     */
    public function it_starts_a_new_message()
    {
        $this->assertInstanceOf(MessageFactory::class, $this->user->newMessage());
    }

    /** @test */
    public function it_instantiates_a_credit_transaction()
    {
        $this->assertInstanceOf(CreditTransaction::class, $this->user->recordNewCreditTransaction());
    }

}

