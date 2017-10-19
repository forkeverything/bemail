<?php

namespace Tests\Unit;

use App\Translation\Recipient;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipientTest extends TestCase
{
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
