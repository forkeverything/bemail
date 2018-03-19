<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Registered' => [
            'App\Listeners\SendWelcomeMail'
        ],
        'App\Translation\Events\NewMessageRequestReceived' => [
            'App\Translation\Listeners\CreateNewMessageModel',
            'App\Translation\Listeners\CreateRecipientsForNewMessage',
            'App\Translation\Listeners\CreateAttachmentsForNewMessage',
            'App\Translation\Listeners\TranslateNewMessage',
            'App\Payments\Listeners\ProcessMessagePayment',
            'App\Translation\Listeners\SendNewMessageRequestReceivedNotification'
        ],
        'App\Translation\Events\MessageTranslated' => [
            'App\Translation\Listeners\SaveTranslatedMessage',
            'App\Translation\Listeners\UpdateOrderStatusToComplete',
            'App\Translation\Listeners\SendTranslatedMessageMail',
            'App\Translation\Listeners\SendMessageHasBeenTranslatedNotificationMail',
        ],
        'App\Translation\Events\ReplyReceived' => [
            'App\Translation\Listeners\CreateReplyMessageModel',
            'App\Translation\Listeners\CreateRecipientsForReplyMessage',
            'App\Translation\Listeners\TranslateReply',
            'App\Payments\Listeners\ProcessMessagePayment',
            'App\Translation\Listeners\SendReplyReceivedNotification'
        ],
        'App\Translation\Events\ReplyErrorOccurred' => [
            'App\Translation\Listeners\SendReplyNotSentNotification'
        ],
        'App\Translation\Events\TranslationErrorOccurred' => [
            'App\Translation\Listeners\UpdateOrderStatusToError',
            'App\Translation\Listeners\RecordTranslationError',
            'App\Translation\Listeners\NotifyAdminsOfTranslationError'
        ],
        'App\Payments\Events\CustomerSubscriptionDeleted' => [
            'App\Payments\Listeners\CancelSubscription',
            'App\Payments\Listeners\SendSubscriptionCancelledNotification'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
