<?php

namespace Tests\Unit;

use App\Language;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LanguageTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_is_be_able_to_mass_assign_these_fields()
    {
        $fields = [
            'name' => 'Gibberish',
            'code' => 'i2y3biy23'
        ];

        $language = Language::create($fields);

        foreach ($fields as $key => $value) {
            $this->assertEquals($language->{$key}, $value);
        }
    }

    /**
     * @test
     */
    public function it_finds_a_language_by_code()
    {
        $languages = Language::all()->toArray();
        foreach ($languages as $language) {
            $this->assertEquals(Language::findByCode($language["code"])->name, $language["name"]);
        }
    }

    /**
     * @test
     */
    public function it_finds_english()
    {
        $this->assertEquals('en', Language::english()->code);
    }

    /**
     * @test
     */
    public function it_finds_japanese()
    {
        $this->assertEquals('ja', Language::japanese()->code);
    }


    /**
     * @test
     */
    public function it_finds_chinese()
    {
        $this->assertEquals('zh', Language::chinese()->code);
    }

}
