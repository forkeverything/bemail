<?php

namespace App\Providers;

use App\Listeners\SendWelcomeMail;
use App\Payment\Events\CustomerSubscriptionDeleted;
use App\Payment\Events\FailedChargingUserForMessage;
use App\Payment\Listeners\CancelSubscription;
use App\Payment\Listeners\SendChargeFailureNotifications;
use App\Payment\Listeners\ProcessMessagePayment;
use App\Payment\Listeners\SendSubscriptionCancelledNotification;
use App\Translation\Events\FailedCreatingReply;
use App\Translation\Events\MessageTranslated;
use App\Translation\Events\NewMessageCreated;
use App\Translation\Events\ReplyMessageCreated;
use App\Translation\Events\TranslationErrorOccurred;
use App\Translation\Listeners\CancelTranslationOrder;
use App\Translation\Listeners\SendReplyNotSentDueToSystemErrorNotification;
use App\Translation\Listeners\SendSystemTranslationErrorAdminNotification;
use App\Translation\Listeners\SendMessageNotTranslatedDueToSystemErrorNotification;
use App\Translation\Listeners\RecordTranslationError;
use App\Translation\Listeners\SaveTranslatedMessage;
use App\Translation\Listeners\SendMessageTranslatedNotification;
use App\Translation\Listeners\SendNewMessageWillBeTranslatedNotification;
use App\Translation\Listeners\SendReplyMessageWillBeTranslatedNotification;
use App\Translation\Listeners\SendTranslatedMessage;
use App\Translation\Listeners\TranslateNewMessage;
use App\Translation\Listeners\TranslateReplyMessage;
use App\Translation\Listeners\UpdateOrderStatusToComplete;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
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
        ReplyMessageCreated::class => [
            TranslateReplyMessage::class,
            ProcessMessagePayment::class,
            SendReplyMessageWillBeTranslatedNotification::class
        ],
        MessageTranslated::class => [
            SaveTranslatedMessage::class,
            UpdateOrderStatusToComplete::class,
            SendTranslatedMessage::class,
            SendMessageTranslatedNotification::class,
        ],
        FailedCreatingReply::class => [
            SendReplyNotSentDueToSystemErrorNotification::class
            // Notify admin
        ],
        TranslationErrorOccurred::class => [
            RecordTranslationError::class,
            SendMessageNotTranslatedDueToSystemErrorNotification::class,
            SendSystemTranslationErrorAdminNotification::class
        ],
        FailedChargingUserForMessage::class => [
            CancelTranslationOrder::class,
            SendChargeFailureNotifications::class
            // TODO ::: Record Charge error on User
        ],
        CustomerSubscriptionDeleted::class => [
            CancelSubscription::class,
            SendSubscriptionCancelledNotification::class
        ]
    ];
}
