<?php

namespace Tests\Unit\Translation;

use App\Translation\Message;
use App\Translation\Reply;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageThreadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_build_a_messages_thread()
    {

        $messageIds = [];
        $targetMessage = null;

        // Starting message
        $firstMessage = factory(Message::class)->create();
        $messageIds[] = $firstMessage->id;

        $firstReplies = factory(Reply::class, 3)->create([
            'original_message_id' => $firstMessage->id
        ]);

        foreach ($firstReplies as $i => $firstReply) {
            $message = factory(Message::class)->create([
                'reply_id' => $firstReply->id
            ]);
            $messageIds[] = $message->id;

            if ($i == 1) {
                $secondReplies = factory(Reply::class, 2)->create([
                    'original_message_id' => $message->id
                ]);
                foreach ($secondReplies as $v => $secondReply) {
                    $message = factory(Message::class)->create([
                        'reply_id' => $secondReply->id
                    ]);
                    $messageIds[] = $message->id;

                    if($v == 0) {
                        $targetMessage = $message;
                    }
                }
            }
        }

        /**
         * Messages Heirarchy
         * M = message created order and order of messageIds
         * T = Expected position in thread
         *
         * M0T5 => [
         *     M1T3,
         *     M2T2 => [
         *              M3T0,
         *              M4T1
         *          ],
         *     M5T4
         * ]
         */

        // message ids index => thread ids index
        $correspondingMessages = [
            3 => 0,
            4 => 1,
            2 => 2,
            1 => 3,
            5 => 4,
            0 => 5
        ];

        $thread = $targetMessage->thread();

        $this->assertEquals(count($messageIds), $thread->count());

        $threadIds = $thread->pluck('id')->toArray();

        // Test the order
        foreach($correspondingMessages as $messageIdIndex => $threadIdIndex) {
            $this->assertEquals($messageIds[$messageIdIndex], $threadIds[$threadIdIndex]);
        }
    }
}
