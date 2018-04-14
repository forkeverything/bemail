<?php

namespace App;

use App\Translation\Message;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Error
 *
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $errorable
 * @mixin \Eloquent
 */
class Error extends Model
{

    /**
     * Mass-fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'msg',
        'errorable_id',
        'errorable_type'
    ];

    /**
     * Polymorphic parent model.
     *
     * This could be a different model depending on the exact
     * error record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function errorable()
    {
        return $this->morphTo();
    }

    /**
     * Instantiate for given Message.
     *
     * @param Message $message
     * @return static
     */
    public static function newForMessage(Message $message)
    {
        return new static([
            'errorable_id' => $message->id,
            'errorable_type' => get_class($message)
        ]);
    }


    /**
     * Instantiate for given User.
     *
     * @param User $user
     * @return static
     */
    public static function newForUser(User $user)
    {
        return new static([
            'errorable_id' => $user->id,
            'errorable_type' => get_class($user)
        ]);
    }

    /**
     * Code to identify the error.
     *
     * @param null $code
     * @return $this|mixed
     */
    public function code($code = null)
    {
        if (is_null($code)) {
            return $this->code;
        }
        $this->code = $code;
        return $this;
    }

    /**
     * Description of the error.
     *
     * @param null $msg
     * @return $this|mixed
     */
    public function msg($msg = null)
    {
        if (is_null($msg)) {
            return $this->msg;
        }
        $this->msg = $msg;
        return $this;
    }

}
