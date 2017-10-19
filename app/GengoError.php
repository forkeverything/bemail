<?php

namespace App;

use App\Translation\Message;
use Illuminate\Database\Eloquent\Model;

class GengoError extends Model
{
    protected $fillable = [
        'code',
        'description',
        'message_id'
    ];

    /**
     * Records a GengoError for a given Message from a response from the Gengo API.
     *
     * @param Message $message
     * @param $gengoResponse
     */
    public static function record(Message $message, $gengoResponse)
    {
        static::create([
            'code' => $gengoResponse["err"]["code"],
            'description' => $gengoResponse["err"]["msg"],
            'message_id' => $message->id
        ]);
    }

}
