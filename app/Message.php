<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function translationStatus()
    {
        return $this->belongsTo(TranslationStatus::class, 'translation_status_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
