<?php

namespace App;

use App\Payments\CreditTransaction;
use App\Payments\Plan;
use App\Translation\Message;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;

/**
 * App\User
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $remember_token
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $credits
 * @property string|null $stripe_id
 * @property string|null $card_brand
 * @property string|null $card_last_four
 * @property string|null $trial_ends_at
 * @property int $language_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Payments\CreditTransaction[] $creditTransactions
 * @property-read \App\Language $defaultLanguage
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Message[] $messages
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Recipient[] $recipients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Payments\Subscription[] $subscriptions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCardBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCardLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCredits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable, Billable;

    /**
     * Mass-assignable Fields
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'credits',
        'language_id'
    ];

    /**
     * Hidden Fields
     *
     * These properties don't show up when you
     * retrieve the model's array. ie. When
     * you call User::all().
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * User's default language
     *
     * We assume this is the language that
     * the User would want received mail
     * to be translated into.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function defaultLanguage()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    /**
     * Message(s) sent by User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Recipient(s) that User has sent Message(s) to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function recipients()
    {
        return $this->hasManyThrough('App\Translation\Recipient', 'App\Translation\Message', 'user_id', 'message_id');
    }

    /**
     * Every credit transaction that adjusted User credits.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function creditTransactions()
    {
        return $this->hasMany(CreditTransaction::class);
    }

    /**
     * Payment plan.
     *
     * @return Plan
     */
    public function plan()
    {
        return Plan::forUser($this);
    }

    /**
     * Credits that can be used for payment.
     *
     * @param null $amount
     * @return int
     */
    public function credits($amount = null)
    {
        if (is_null($amount)) {
            return $this->credits;
        }
        $this->credits = $amount;
        $this->save();
    }
}
