<?php

namespace Tests\Unit;

use App\Translation\TranslationStatus;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TranslationStatusTest extends TestCase
{
    /**
     * Use static methods to fetch the right
     * record for a specific status.
     *
     * @test
     */
    public function it_fetches_the_right_concrete_record()
    {
        $statuses = [
            'available',
            'pending',
            'approved',
            'error'
        ];
        foreach($statuses as $status) {
            $this->assertEquals(TranslationStatus::$status()->description, $status);
        }
    }
}
