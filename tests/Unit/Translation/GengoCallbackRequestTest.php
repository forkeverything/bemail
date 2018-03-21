<?php

namespace Tests\Unit\Translation;

use App\Translation\Translators\Gengo\GengoCallbackRequest;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GengoCallbackRequestTest extends TestCase
{

    /**
     * Callback HTTP Post request.
     *
     * @var Request
     */
    private $request;
    /**
     * @var GengoCallbackRequest
     */
    private $gengoRequest;

    protected function setUp()
    {
        parent::setUp();
        $this->request = new Request();
        $this->gengoRequest = new GengoCallbackRequest($this->request);
    }

    /** @test */
    public function it_instantiates_from_http_request()
    {
        $this->assertInstanceOf(GengoCallbackRequest::class, $this->gengoRequest);
    }

    /** @test */
    public function it_checks_whether_callback_is_for_a_job()
    {
        $this->assertFalse($this->gengoRequest->isJobRequest());
        $this->request["job"] = json_encode([
            "job_id" => 12345
        ]);
        $this->assertTrue($this->gengoRequest->isJobRequest());
    }

    /** @test */
    public function it_gets_the_message_hash()
    {
        $messageHash = "12345";
        $this->request["job"] = json_encode([
            'custom_data' => json_encode([
                'message_hash' => $messageHash
            ])
        ]);

        $this->assertEquals($messageHash, $this->gengoRequest->messageHash());
    }

    /** @test */
    public function it_gets_job_status()
    {
        $status = "cancelled";
        $this->request["job"] = json_encode([
            'status' => $status
        ]);
        $this->assertEquals($status, $this->gengoRequest->status());
    }

    /** @test */
    public function it_gets_the_translated_body()
    {
        $translatedBody = 'This is some translated text.';
        $this->request["job"] = json_encode([
            'body_tgt' => $translatedBody
        ]);
        $this->assertEquals($translatedBody, $this->gengoRequest->translatedBody());
    }

}
