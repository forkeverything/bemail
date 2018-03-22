<?php

namespace Tests\Unit\Translation\Message;

use App\Http\Requests\CreateMessageRequest;
use App\Language;
use App\Translation\Message\NewMessageFields;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewMessageFieldsTest extends TestCase
{

    /**
     * @var CreateMessageRequest
     */
    private $request;
    /**
     * @var NewMessageFields
     */
    private $fields;
    /**
     * @var string
     */
    private $subject = 'Some Message';
    /**
     * @var string 　
     */
    private $body = 'Please translate this text.';
    /**
     * @var bool
     */
    private $autoTranslateReply = null;
    /**
     * @var bool
     */
    private $sendToSelf = 'on';
    /**
     * @var string
     */
    private $langSrc = 'en';
    /**
     * @var string
     */
    private $langTgt = 'ja';
    /**
     * @var string
     */
    private $recipients = 'john@example.com,sarah@example.com';
    /**
     * @var array
     */
    private $attachments = [
        'file_1', 'file_2', 'file_3'
    ];

    protected function setUp()
    {
        parent::setUp();

        $this->request = new CreateMessageRequest([
            'subject' => $this->subject,
            'body' => $this->body,
            'auto_translate_reply' => $this->autoTranslateReply,
            'send_to_self' => $this->sendToSelf,
            'lang_src' => $this->langSrc,
            'lang_tgt' => $this->langTgt,
            'recipients' => $this->recipients,
            'attachments' => $this->attachments
        ]);

        $this->fields = new NewMessageFields($this->request);
    }

    /** @test */
    public function it_instantiates_from_a_create_message_request()
    {
        $this->assertInstanceOf(NewMessageFields::class, $this->fields);
    }

    /** @test */
    public function it_gets_the_subject()
    {
        $this->assertEquals($this->subject, $this->fields->subject());
    }

    /** @test */
    public function it_gets_the_body()
    {
        $this->assertEquals($this->body, $this->fields->body());
    }

    /** @test */
    public function it_gets_whether_to_auto_translate_body()
    {
        $this->assertFalse($this->fields->autoTranslateReply());
    }

    /** @test */
    public function it_gets_whether_to_send_to_self()
    {
        $this->assertTrue($this->fields->sendToSelf());
    }

    /** @test */
    public function it_gets_the_source_language()
    {
        $this->assertEquals(Language::english()->id, $this->fields->langSrcId());
    }

    /** @test */
    public function it_gets_the_target_language()
    {
        $this->assertEquals(Language::japanese()->id, $this->fields->langTgtId());
    }

    /** @test */
    public function it_gets_the_recient_list()
    {
        $this->assertEquals($this->recipients, $this->fields->recipients());
    }

    /** @test */
    public function it_gets_attachments()
    {
        $this->assertEquals($this->attachments, $this->fields->attachments());
    }


}
