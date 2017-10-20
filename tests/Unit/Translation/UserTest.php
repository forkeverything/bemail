<?php

namespace Tests\Unit;

use App\Language;
use App\Translation\Message;
use App\Translation\Recipient;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    use DatabaseTransactions;

    private static $user;

    /**
     * Set / run these before each test
     */
    public function setUp()
    {
        parent::setUp();
        static::$user = factory(User::class)->create();
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
            'word_credits' => 10,
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
        $fields = array_keys(static::$user->toArray());
        $this->assertNotContains('password', $fields);
        $this->assertNotContains('remember_token', $fields);
    }

    /**
     * @test
     */
    public function it_has_a_default_language()
    {
        $this->assertInstanceOf('App\Language', static::$user->defaultLanguage);
    }
    
    /**
     * @test
     */
    public function it_fetches_user_messages()
    {
        $this->assertCount(0, static::$user->messages);
        factory(Message::class, 3)->create([
            'user_id' => static::$user->id
        ]);

        $this->assertCount(3, static::$user->fresh()->messages);
    }

    /**
     * @test
     */
    public function it_fetches_get_user_recipients()
    {
        $this->assertCount(0, static::$user->recipients);
        factory(Recipient::class, 5)->create([
            'user_id' => static::$user->id
        ]);
        $this->assertCount(5, static::$user->fresh()->recipients);
    }
}

