<?php

namespace Tests\Unit\Translation\Translators;

use App\Translation\Contracts\Translator;
use App\Translation\Message;
use App\Translation\Translators\GengoTranslator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GengoTranslatorTest extends TestCase
{

    use DatabaseTransactions;

    protected $gengoTranslator;

    // Keys each language pair should return
    protected $languagePairKeys;

    // Gengo error response
    protected $errorCode = '808';
    protected $errorMsg = 'no good';

    public function setUp()
    {
        parent::setup();
        $this->gengoTranslator = $this->app->make(Translator::class);
        $this->gengoTranslator->test = true;

        $this->languagePairKeys = [
            'lc_src',
            'lc_tgt',
            'tier',
            'unit_price',
            'currency'
        ];
    }

    /**
     * @test
     */
    public function it_uses_gengo_translator()
    {
        $this->assertInstanceOf(GengoTranslator::class, $this->gengoTranslator);
    }

    /**
     * @test
     */
    public function it_fetches_all_language_pairs()
    {
        $languagePairs = $this->gengoTranslator->getLanguagePairs();
        $numLanguages = count($languagePairs);
        $numPairsToTest = 5;

        // Assume Gengo has more than 5 language pairs and check first 5 pairs.
        $this->assertTrue($numLanguages > $numPairsToTest);
        $testArray = array_slice($languagePairs, 0, $numPairsToTest);

        foreach ($testArray as $languagePair) {
            foreach($this->languagePairKeys as $key) {
                $this->assertTrue(isset($languagePair->$key));
            }
        }
    }

    /**
     * @test
     */
    public function it_fetches_filtered_language_pair()
    {
        $filteredLanguagePair = $this->gengoTranslator->getLanguagePairs('en', 'zh');

        // Reset to remove original language pair index from Gengo,
        // this should just return the single object.
        $resetLanguagePair = reset($filteredLanguagePair);

        foreach($this->languagePairKeys as $key) {
            $this->assertTrue(isset($resetLanguagePair->$key));
        }
    }

    /**
     * @test
     */
    public function it_posts_a_successful_gengo_job()
    {
        $message = factory(Message::class)->create();
        $status = $this->gengoTranslator->translate($message);
        $this->assertEquals("ok", $status);
    }

    /**
     * @test
     */
    public function it_parses_out_job_error()
    {
        $response = [
            'err' => [
                'jobs_01' => [
                    [
                        'code' => $this->errorCode,
                        'msg' => $this->errorMsg
                    ]
                ]
            ]
        ];

        $error = $this->gengoTranslator->parseErrorFromResponse($response);

        $this->assertEquals($this->errorCode, $error['code']);
        $this->assertEquals("Gengo Job: $this->errorMsg", $error['description']);
    }

    /**
     * @test
     */
    public function it_parses_out_system_error()
    {
        $response = [
            'err' => [
                'code' => $this->errorCode,
                'msg' => $this->errorMsg
            ]
        ];
        $error = $this->gengoTranslator->parseErrorFromResponse($response);

        $this->assertEquals($this->errorCode, $error['code']);
        $this->assertEquals("Gengo System: $this->errorMsg", $error['description']);
    }

}
