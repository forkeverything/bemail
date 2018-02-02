<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Language
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereName($value)
 * @mixin \Eloquent
 */
class Language extends Model
{

    protected $fillable = [
        'name',
        'code'
    ];

    /**
     * Returns Language model given iso
     * 2 character string code.
     *
     * @param $code
     * @return mixed
     */
    public static function findByCode($code)
    {
        return static::where('code', $code)->firstOrFail();
    }
}
