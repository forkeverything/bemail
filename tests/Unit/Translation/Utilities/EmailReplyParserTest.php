<?php

namespace Tests\Unit\Translation\Utilities;

use App\Translation\Utilities\EmailReplyParser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailReplyParserTest extends TestCase
{
    /**
     * @test
     */
    public function it_strips_text_starting_from_reply_line()
    {
        $standardReply = "Dear Foo,
    
        Idque Caesaris facere voluntate liceret: sese habere. 
        Vivamus sagittis lacus vel augue laoreet rutrum faucibus.
    
        On 31st Jan 2018, you wrote:
    
        --- Please type your reply above this line ---
    
        Inmensae subtilitatis, obscuris et malesuada fames. 
        Phasellus laoreet lorem vel dolor tempus vehicula. 
        Salutantibus vitae elit libero, a pharetra augue. 
        Integer legentibus erat a ante historiarum dapibus. 
        Quae vero auctorem tractata ab fiducia dicuntur.
        ";

        $expected = "Dear Foo,
    
        Idque Caesaris facere voluntate liceret: sese habere. 
        Vivamus sagittis lacus vel augue laoreet rutrum faucibus.
    
        On 31st Jan 2018, you wrote:";

        $stripped = EmailReplyParser::parse($standardReply);
        $this->assertEquals($expected, $stripped);
    }

    /**
     * @test
     */
    public function it_includes_all_the_text_when_there_is_no_reply_line()
    {
        $reply = "Inmensae subtilitatis, obscuris et malesuada fames. 
        Phasellus laoreet lorem vel dolor tempus vehicula. 
        Salutantibus vitae elit libero, a pharetra augue. 
        Integer legentibus erat a ante historiarum dapibus. 
        Quae vero auctorem tractata ab fiducia dicuntur.";
        $this->assertEquals($reply, EmailReplyParser::parse($reply));
    }
}
