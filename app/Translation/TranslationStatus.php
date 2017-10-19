<?php

namespace App\Translation;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Translation\TranslationStatus
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\TranslationStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\TranslationStatus whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\TranslationStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\TranslationStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TranslationStatus extends Model
{

    /**
     * Available.
     * Submitted by User and is in queue.
     *
     * @return mixed
     */
    public static function available()
    {
        return TranslationStatus::where('description', 'available')->first();
    }

    /**
     * Pending.
     * Work has begun on translating the Message.
     *
     * @return mixed
     */
    public static function pending()
    {
        return TranslationStatus::where('description', 'pending')->first();
    }

    /**
     * Approved.
     * Translation is complete and approved.
     *
     * @return mixed
     */
    public static function approved()
    {
        return TranslationStatus::where('description', 'approved')->first();
    }

    /**
     * Error
     * Not in queue and will not be translated. Usually
     * means bemail system error and is not the User's
     * fault.
     *
     * @return mixed
     */
    public static function error()
    {
        return TranslationStatus::where('description', 'error')->first();
    }
}