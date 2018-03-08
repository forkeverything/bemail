<?php

namespace Tests\Unit\Translation\Translators;

use App\Translation\Contracts\Translator;
use App\Translation\Exceptions\TranslationException;
use App\Translation\Message;
use App\Translation\Translators\GengoTranslator;
use Gengo\Exception;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GengoTranslatorTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * Translator Instance
     *
     * @var GengoTranslator
     */
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
     * @throws
     */
    public function it_fetches_all_language_pairs()
    {
        $languagePairs = $this->gengoTranslator->languagePair();
        $numLanguages = count($languagePairs);
        $numPairsToTest = 5;

        // Assume Gengo has more than 5 language pairs and check first 5 pairs.
        $this->assertTrue($numLanguages > $numPairsToTest);
        $testArray = array_slice($languagePairs, 0, $numPairsToTest);

        foreach ($testArray as $languagePair) {
            foreach ($this->languagePairKeys as $key) {
                $this->assertTrue(isset($languagePair[$key]));
            }
        }
    }

    /**
     * @test
     * @throws
     */
    public function it_fetches_filtered_language_pair()
    {
        $filteredLanguagePair = $this->gengoTranslator->languagePair('en', 'zh');

        // Reset to remove original language pair index from Gengo,
        // this should just return the single object.
        $resetLanguagePair = reset($filteredLanguagePair);
        foreach ($this->languagePairKeys as $key) {
            $this->assertTrue(isset($resetLanguagePair[$key]));
        }
    }

    /**
     * @test
     * @throws
     */
    public function it_translates_a_message()
    {
        $message = factory(Message::class)->create();
        $this->assertNull($message->gengoOrderId());
        $this->gengoTranslator->translate($message);
        $this->assertNotNull($message->fresh()->gengoOrderId());
    }

}
