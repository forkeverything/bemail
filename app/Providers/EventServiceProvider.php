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
        'App\Translation\Events\NewMessageCreated' => [
            'App\Translation\Listeners\TranslateNewMessage',
            'App\Payment\Listeners\ProcessMessagePayment',
            'App\Translation\Listeners\SendNewMessageWillBeTranslatedNotification'
        ],
        'App\Translation\Events\MessageTranslated' => [
            'App\Translation\Listeners\SaveTranslatedMessage',
            'App\Translation\Listeners\UpdateOrderStatusToComplete',
            'App\Translation\Listeners\SendTranslatedMessageToRecipients',
            'App\Translation\Listeners\SendMessageTranslatedNotification',
        ],
        'App\Translation\Events\ReplyMessageCreated' => [
            'App\Translation\Listeners\TranslateReply',
            'App\Payment\Listeners\ProcessMessagePayment',
            'App\Translation\Listeners\SendReplyMessageWillBeTranslatedNotification'
        ],
        'App\Translation\Events\FailedCreatingReply' => [
            // Tell sender that reply not sent due to system error
            // Notify admin
        ],
        'App\Translation\Events\TranslationErrorOccurred' => [
            'App\Translation\Listeners\NotifySenderOfTranslationFailureDueToSystemError',
            'App\Translation\Listeners\RecordTranslationError',
            'App\Translation\Listeners\NotifyAdminsOfTranslationError'
        ],
        'App\Payment\Events\FailedChargingUserForMessage' => [
            'App\Translation\Listeners\CancelTranslationOrder',
            'App\Payment\Listeners\NotifySenderThatMessageNotSentDueToChargeFailure'
            // TODO ::: Record Charge error on User
        ],
        'App\Payment\Events\CustomerSubscriptionDeleted' => [
            'App\Payment\Listeners\CancelSubscription',
            'App\Payment\Listeners\SendSubscriptionCancelledNotification'
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
