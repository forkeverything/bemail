<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TranslationStatus extends Model
{

    /**
     * Pending
     *
     * @return mixed
     */
    public static function pending()
    {
        return TranslationStatus::where('description', 'pending')->first();
    }

    /**
     * Currently Translating
     * @return mixed
     */
    public static function translating()
    {
        return TranslationStatus::where('description', 'translating')->first();
    }

    /**
     * Complete
     * @return mixed
     */
    public static function complete()
    {
        return TranslationStatus::where('description', 'complete')->first();
    }
}