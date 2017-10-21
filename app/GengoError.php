<?php

namespace App;

use App\Translation\Message;
use Illuminate\Database\Eloquent\Model;

/**
 * App\GengoError
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $code
 * @property string $description
 * @property int $message_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GengoError whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GengoError whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GengoError whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GengoError whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GengoError whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GengoError whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
     * @return $this|Model
     */
    public static function record(Message $message, $gengoResponse)
    {
        // Error could be job based (ie. unsupported language pair) or system (not enough gengo credits).
        $isJobError = array_key_exists("jobs_01", $gengoResponse["err"]);

        $code = $isJobError ? $gengoResponse["err"]["jobs_01"][0]["code"] : $gengoResponse["err"]["code"];
        $msg = $isJobError ? $gengoResponse["err"]["jobs_01"][0]["msg"] : $gengoResponse["err"]["msg"];
        $description = $isJobError ? "job: {$msg}" : "system: {$msg}";

        return static::create([
            'code' => $code,
            'description' => $description,
            'message_id' => $message->id
        ]);
    }

}
