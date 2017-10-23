<?php

namespace Tests\Unit\Translation;

use App\Translation\Message;
use App\Translation\MessageError;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageErrorTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_mass_assign_these_fields()
    {
        $fields = [
            'code' => 8888,
            'description' => 'Some message error.',
            'message_id' => factory(Message::class)->create()->id
        ];
        $error = MessageError::create($fields);
        foreach($fields as $key => $value) {
            $this->assertEquals($error->{$key}, $value);
        }
    }
}
