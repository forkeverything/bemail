<?php

namespace Tests\Unit;

use App\GengoError;
use App\Translation\Message;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GengoErrorTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_mass_assign_these_fields()
    {
        $fields = [
            'code' => 8888,
            'description' => 'Some gengo error.',
            'message_id' => factory(Message::class)->create()->id
        ];
        $error = GengoError::create($fields);
        foreach($fields as $key => $value) {
            $this->assertEquals($error->{$key}, $value);
        }
    }
    
    /**
     * @test
     */
    public function it_persists_a_gengo_error_in_the_database()
    {
        $message = factory(Message::class)->create();
        $response = [
            "err" => [
                "code" => 4444,
                "msg" => "failz."
            ]
        ];
        GengoError::record($message, $response);
        $this->assertNotNull(GengoError::where('code', 4444)
                                       ->where('description', 'system: failz.')
                                       ->first());
    }

}
