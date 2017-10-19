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
        // Error could be job based (ie. unsupported language pair) or system (not enough gengo credits).
        $isJobError = array_key_exists("jobs_01", $gengoResponse["err"]);

        $code = $isJobError ? $gengoResponse["err"]["jobs_01"][0]["code"] : $gengoResponse["err"]["code"];
        $msg = $isJobError ? $gengoResponse["err"]["jobs_01"][0]["msg"] : $gengoResponse["err"]["msg"];
        $description = $isJobError ? "job: {$msg}" : "system: {$msg}";

        static::create([
            'code' => $code,
            'description' => $description,
            'message_id' => $message->id
        ]);
    }

}
