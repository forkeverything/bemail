<?php

namespace Tests\Unit;

use App\Error;
use App\Translation\Message;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ErrorTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * Generated Error.
     *
     * @var Error
     */
    private $error;

    protected function setUp()
    {
        parent::setUp();
        $this->error = factory(Error::class)->create();
    }

    /**
     * @test
     */
    public function it_can_mass_assign_these_fields()
    {
        $message = factory(Message::class)->create();
        $fields = [
            'code' => 8888,
            'msg' => 'Some message error.',
            'errorable_id' => $message->id,
            'errorable_type' => get_class($message)
        ];

        $error = Error::create($fields);
        foreach($fields as $key => $value) {
            $this->assertEquals($error->{$key}, $value);
        }
    }

    /** @test */
    public function it_retrieves_the_polymorphic_parent_model()
    {
        $message = factory(Message::class)->create();
        $error = factory(Error::class)->create([
            'errorable_id' => $message->id,
            'errorable_type' => get_class($message)
        ]);
        $this->assertEquals($message->id, $error->errorable->id);
    }

    /** @test */
    public function it_instantiates_for_given_message()
    {
        $message = factory(Message::class)->create();
        $error = Error::newForMessage($message);
        $this->assertEquals($message->id, $error->errorable_id);
        $this->assertEquals(get_class($message), $error->errorable_type);
    }

    /** @test */
    public function it_has_an_error_code()
    {
        $code = '66666666';
        $this->assertTrue(is_int($this->error->code()));
        $this->error->code($code);
        $this->assertEquals($code, $this->error->code());
    }

    /** @test */
    public function it_has_an_error_message()
    {
        $msg = 'foobar';
        $this->assertTrue(is_string($this->error->msg()));
        $this->error->msg($msg);
        $this->assertEquals($msg, $this->error->msg());
    }

}
