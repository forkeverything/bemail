<?php

namespace App\Translation;

use Illuminate\Database\Eloquent\Model;

class MessageError extends Model
{
    /**
     * Mass-assignable fields
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'description',
        'message_id'
    ];

    /**
     * Automatically instantiate these properties as Carbon instances
     *
     * @var array
     */
    protected $dates = [
        'created_at'
    ];
}
