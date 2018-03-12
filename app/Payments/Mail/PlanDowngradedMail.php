<?php

namespace App\Payments\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PlanDowngradedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * User that got downgraded.
     *
     * @var User
     */
    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Account Downgraded')
                    ->markdown('emails.payments.plan-downgraded');
    }
}
