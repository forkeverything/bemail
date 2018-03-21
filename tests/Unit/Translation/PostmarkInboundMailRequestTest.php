<?php

namespace Tests\Unit\Translation;

use App\InboundMail\Postmark\PostmarkInboundMailRequest;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostmarkInboundMailRequestTest extends TestCase
{

    /**
     * Inbound HTTP POST request from Postmark.
     *
     * @var
     */
    private $request;
    /**
     * @var PostmarkInboundMailRequest
     */
    private $postmarkRequest;

    // Fake fields
    private $name = "john dough";
    private $address = 'john@example.com';
    private $subject = "Please Read";
    private $body = 'some text here.';
    private $attachments = [
        'foo',
        'bar',
        'baz',
    ];
    private $recipientTypes = [
        "ToFull" => "standard",
        "CcFull" => "cc",
        "BccFull" => "bcc",
    ];
    private $emails = [
        'john@example.com',
        'sara@example.com'
    ];

    protected function setUp()
    {
        parent::setUp();

        $this->request = new Request();

        $this->request["FromName"] = $this->name;
        $this->request["From"] = $this->address;
        $this->request["Subject"] = $this->subject;
        $this->request["TextBody"] = $this->body;
        $this->request["Attachments"] = $this->attachments;

        $recipients = [
            ["Email" => $this->emails[0]],
            ["Email" => $this->emails[1]]
        ];
        foreach ($this->recipientTypes as $field => $type) {
            $this->request[$field] = $recipients;
        }

        $this->postmarkRequest = new PostmarkInboundMailRequest($this->request);
    }

    /**
     * @test
     */
    public function it_instantiates_from_a_http_request()
    {
        $this->assertInstanceOf(PostmarkInboundMailRequest::class, $this->postmarkRequest);
    }

    /**
     * @test
     */
    public function it_gets_from_name()
    {
        $this->assertEquals($this->name, $this->postmarkRequest->fromName());
    }

    /**
     * @test
     */
    public function it_gets_from_address()
    {
        $this->assertEquals($this->address, $this->postmarkRequest->fromAddress());
    }

    /**
     * @test
     */
    public function it_gets_email_subject()
    {
        $this->assertEquals($this->subject, $this->postmarkRequest->subject());
    }

    /**
     * @test
     */
    public function it_gets_stripped_text_body()
    {
        $this->assertEquals($this->body, $this->postmarkRequest->strippedReplyBody());
    }


    /**
     * @test
     */
    public function it_gets_email_attachments()
    {
        $this->assertEquals($this->attachments, $this->postmarkRequest->attachments());
    }

    /**
     * @test
     */
    public function it_gets_various_recipients()
    {

        foreach ($this->recipientTypes as $field => $type) {
            foreach ($this->emails as $index => $email) {
                $this->assertEquals($email, call_user_func([$this->postmarkRequest, "{$type}Recipients"])[$index]->email());
            }
        }
    }

    /**
     * @test
     */
    public function it_gets_the_mails_intended_action()
    {
        $address = "reply_12345@bemail.io";
        $this->request["OriginalRecipient"] = $address;
        $this->postmarkRequest = new PostmarkInboundMailRequest($this->request);
        $this->assertEquals('reply', $this->postmarkRequest->action());
    }

    /**
     * @test
     */
    public function it_gets_the_mails_intended_target()
    {
        $address = "message_12345@bemail.io";
        $this->request["OriginalRecipient"] = $address;
        $this->postmarkRequest = new PostmarkInboundMailRequest($this->request);
        $this->assertEquals('12345', $this->postmarkRequest->target());
    }


}
