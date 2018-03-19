<?php

namespace Tests\Unit\Translation;

use App\Translation\PostmarkInboundMailRequest;
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

    protected function setUp()
    {
        parent::setUp();
        $this->request = new Request();
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
        $name = "john dough";
        $this->request["FromName"] = $name;
        $this->assertEquals($name, $this->postmarkRequest->fromName());
    }

    /**
     * @test
     */
    public function it_gets_from_address()
    {
        $address = 'john@example.com';
        $this->request["From"] = $address;
        $this->assertEquals($address, $this->postmarkRequest->fromAddress());
    }

    /**
     * @test
     */
    public function it_gets_email_subject()
    {
        $subject = "Please Read";
        $this->request["Subject"] = $subject;
        $this->assertEquals($subject, $this->postmarkRequest->subject());
    }

    /**
     * @test
     */
    public function it_gets_stripped_text_body()
    {
        $body = 'some text here.';
        $this->request["TextBody"] = $body;
        $this->assertEquals($body, $this->postmarkRequest->strippedTextBody());
    }


    /**
     * @test
     */
    public function it_gets_email_attachments()
    {
        $attachments = [
            'foo',
            'bar',
            'baz',
        ];
        $this->request["Attachments"] = $attachments;
        $this->assertEquals($attachments, $this->postmarkRequest->attachments());
    }

    /**
     * @test
     */
    public function it_gets_various_recipients()
    {

        $emails = [
            'john@example.com',
            'sara@example.com'
        ];
        $recipients = [
            "{\"Email\": \"$emails[0]\"}",
            "{\"Email\": \"$emails[1]\"}"
        ];
        $types = [
            "ToFull" => "standard",
            "CcFull" => "cc",
            "BccFull" => "bcc",
        ];
        foreach ($types as $key => $type) {
            $this->request[$key] = $recipients;
            foreach ($emails as $index => $email) {
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
        $this->assertEquals('reply', $this->postmarkRequest->action());
    }

    /**
     * @test
     */
    public function it_gets_the_mails_intended_target()
    {
        $address = "message_12345@bemail.io";
        $this->request["OriginalRecipient"] = $address;
        $this->assertEquals('12345', $this->postmarkRequest->target());
    }


}
