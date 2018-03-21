<?php

namespace Tests\Unit\Translation;

use App\InboundMail\Postmark\PostmarkInboundMailRecipient;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostmarkInboundRecipientTest extends TestCase
{

    /**
     * @var PostmarkRecipient
     */
    private $postmarkRecipient;

    private $name = "John Dough";
    private $email = "john@example.com";

    protected function setUp()
    {
        parent::setUp();

        $this->postmarkRecipient = new PostmarkInboundMailRecipient([
            "Name" => $this->name,
            "Email" => $this->email
        ]);
    }

    /**
     * @test
     */
    public function it_instantiates_from_json_string()
    {
        $this->assertInstanceOf(PostmarkInboundMailRecipient::class, $this->postmarkRecipient);
    }

    /**
     * @test
     */
    public function it_gets_the_email()
    {
        $this->assertEquals($this->email, $this->postmarkRecipient->email());
    }

    /**
     * @test
     */
    public function it_gets_the_name()
    {
        $this->assertEquals($this->name, $this->postmarkRecipient->name());
    }



}
