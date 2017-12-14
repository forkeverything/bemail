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
    public function it_finds_the_right_record_by_code()
    {
        $languages = Language::all()->toArray();
        foreach ($languages as $language) {
            $this->assertEquals(Language::findByCode($language["code"])->name, $language["name"]);
        }
    }
}
