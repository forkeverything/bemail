<?php

namespace App\Translation;

use Illuminate\Database\Eloquent\Model;

class RecipientType extends Model
{
    /**
     * No timestamp fields.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Mass-fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Tracks down the type from given string or fails
     *
     * @param $string
     * @return mixed
     */
    public static function findType($string) {
        return static::where('name', $string)->firstOrFail();
    }

    /**
     * Retrieves the model for 'standard' type.
     *
     * @return RecipientType
     */
    public static function standard()
    {
        return static::where('name', 'standard')->first();
    }

    /**
     * Retrieves the model for 'cc' type.
     *
     * @return RecipientType
     */
    public static function cc()
    {
        return static::where('name', 'cc')->first();
    }

    /**
     * Retrieves the model for 'bcc' type.
     *
     * @return RecipientType
     */
    public static function bcc()
    {
        return static::where('name', 'bcc')->first();
    }
}
