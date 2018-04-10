<?php

namespace App;

use App\Translation\Message;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Error
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $code
 * @property string $msg
 * @property int $errorable_id
 * @property string $errorable_type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $errorable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Error whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Error whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Error whereErrorableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Error whereErrorableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Error whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Error whereMsg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Error whereUpdatedAt($value)
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
