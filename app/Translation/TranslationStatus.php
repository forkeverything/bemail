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
     * Pending
     * Submitted by User and is in queue.
     *
     * @return mixed
     */
    public static function pending()
    {
        return TranslationStatus::where('description', 'pending')->first();
    }

    /**
     * Translating.
     * Work has begun on translating the Message.
     *
     * @return mixed
     */
    public static function translating()
    {
        return TranslationStatus::where('description', 'translating')->first();
    }

    /**
     * Complete
     * Translation is complete.
     *
     * @return mixed
     */
    public static function complete()
    {
        return TranslationStatus::where('description', 'complete')->first();
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