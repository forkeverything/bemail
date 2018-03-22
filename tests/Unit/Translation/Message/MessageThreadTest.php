<?php

namespace Tests\Unit\Translation;

use App\Translation\Message;
use App\Translation\Message\MessageThread;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Stripe\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageThreadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var Message
     */
    protected $message;

    protected function setUp()
    {
        parent::setUp();
        $this->message = factory(Message::class)->create();
    }

    /**
     * @test
     */
    public function it_instantiates_with_a_message()
    {
        $this->assertInstanceOf(MessageThread::class, new MessageThread($this->message));
    }

    /**
     * @test
     */
    public function it_adds_current_message_to_thread()
    {
        $thread = $this->message->thread()->get();
        $this->assertEquals($this->message->id, $thread->first()->id);
    }

    /**
     * @test
     */
    public function it_adds_the_original_message()
    {
        $replyMessage = factory(Message::class)->create([
            'message_id' => $this->message->id
        ]);
        $ids = $replyMessage->thread()->get()->pluck('id')->toArray();
        $this->assertCount(2, $replyMessage->thread()->get());
        $this->assertEquals($replyMessage->id, $ids[0]);
        $this->assertEquals($this->message->id, $ids[1]);
    }

    /**
     * @test
     */
    public function it_adds_siblings_to_the_thread()
    {
        $targetMessage = null;
        $messages = factory(Message::class, 3)->create([
            'message_id' => $this->message->id
        ]);
        $threadIds = $messages[1]->thread()->get()->pluck('id')->toArray();
        $this->assertEquals($messages[1]->id, $threadIds[0]);
        $this->assertEquals($messages[0]->id, $threadIds[1]);
        $this->assertEquals($messages[2]->id, $threadIds[2]);
        $this->assertEquals($this->message->id, $threadIds[3]);
    }

    /**
     * @test
     */
    public function it_builds_a_nested_message_thread()
    {

        $originalMessage = factory(Message::class)->create();

        $firstReplies = factory(Message::class, 3)->create([
            'message_id' => $originalMessage->id
        ]);

        $secondReplies = factory(Message::class, 3)->create([
            'message_id' => $firstReplies[1]->id
        ]);

        $thirdReplies = new \Illuminate\Database\Eloquent\Collection();

        foreach ($secondReplies as $secondReply) {
            $thirdReplies->push(factory(Message::class)->create([
                'message_id' => $secondReply->id
            ]));
        }

        $threadIds = $thirdReplies[1]->thread()->get()->pluck('id')->toArray();

        $this->assertCount(8, $threadIds);

        $this->assertEquals($thirdReplies[1]->id, $threadIds[0]);
        $this->assertEquals($secondReplies[1]->id, $threadIds[1]);
        $this->assertEquals($secondReplies[0]->id, $threadIds[2]);
        $this->assertEquals($secondReplies[2]->id, $threadIds[3]);
        $this->assertEquals($firstReplies[1]->id, $threadIds[4]);
        $this->assertEquals($firstReplies[0]->id, $threadIds[5]);
        $this->assertEquals($firstReplies[2]->id, $threadIds[6]);
        $this->assertEquals($originalMessage->id, $threadIds[7]);
    }

}
