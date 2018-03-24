<?php

namespace App\Providers;

use App\Listeners\SendWelcomeMail;
use App\Payment\Events\CustomerSubscriptionDeleted;
use App\Payment\Events\FailedChargingUserForMessage;
use App\Payment\Listeners\CancelSubscription;
use App\Payment\Listeners\NotifyUsersThatMessageNotSentDueToChargeFailure;
use App\Payment\Listeners\ProcessMessagePayment;
use App\Payment\Listeners\SendSubscriptionCancelledNotification;
use App\Translation\Events\FailedCreatingReply;
use App\Translation\Events\MessageTranslated;
use App\Translation\Events\NewMessageCreated;
use App\Translation\Events\ReplyMessageCreated;
use App\Translation\Events\TranslationErrorOccurred;
use App\Translation\Listeners\CancelTranslationOrder;
use App\Translation\Listeners\NotifyAdminsOfTranslationError;
use App\Translation\Listeners\NotifySenderOfTranslationFailureDueToSystemError;
use App\Translation\Listeners\RecordTranslationError;
use App\Translation\Listeners\SaveTranslatedMessage;
use App\Translation\Listeners\SendMessageTranslatedNotification;
use App\Translation\Listeners\SendNewMessageWillBeTranslatedNotification;
use App\Translation\Listeners\SendReplyMessageWillBeTranslatedNotification;
use App\Translation\Listeners\SendTranslatedMessageToRecipients;
use App\Translation\Listeners\TranslateNewMessage;
use App\Translation\Listeners\TranslateReply;
use App\Translation\Listeners\UpdateOrderStatusToComplete;
use Illuminate\Auth\Events\Registered;
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
        Registered::class => [
            SendWelcomeMail::class
        ],
        NewMessageCreated::class => [
            TranslateNewMessage::class,
            ProcessMessagePayment::class,
            SendNewMessageWillBeTranslatedNotification::class
        ],
        MessageTranslated::class => [
            SaveTranslatedMessage::class,
            UpdateOrderStatusToComplete::class,
            SendTranslatedMessageToRecipients::class,
            SendMessageTranslatedNotification::class,
        ],
        ReplyMessageCreated::class => [
            TranslateReply::class,
            ProcessMessagePayment::class,
            SendReplyMessageWillBeTranslatedNotification::class
        ],
        FailedCreatingReply::class => [
            // Tell sender that reply not sent due to system error
            // Notify admin
        ],
        TranslationErrorOccurred::class => [
            NotifySenderOfTranslationFailureDueToSystemError::class,
            RecordTranslationError::class,
            NotifyAdminsOfTranslationError::class
        ],
        FailedChargingUserForMessage::class => [
            CancelTranslationOrder::class,
            NotifyUsersThatMessageNotSentDueToChargeFailure::class
            // TODO ::: Record Charge error on User
        ],
        CustomerSubscriptionDeleted::class => [
            CancelSubscription::class,
            SendSubscriptionCancelledNotification::class
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
