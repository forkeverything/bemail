<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name',
        'code'
    ];

    public static function findByCode($code)
    {
        return static::where('code', $code)->first();
    }
}
