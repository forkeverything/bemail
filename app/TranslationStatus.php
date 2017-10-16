<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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