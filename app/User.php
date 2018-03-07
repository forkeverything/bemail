<?php

namespace App;

use App\Payment\CreditTransaction;
use App\Payment\CreditTransactionType;
use App\Translation\Message;
use App\Translation\Recipient;
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
 * @property int $word_credits
 * @property string|null $stripe_id
 * @property string|null $card_brand
 * @property string|null $card_last_four
 * @property string|null $trial_ends_at
 * @property int $language_id
 * @property-read \App\Language $defaultLanguage
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Message[] $messages
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Recipient[] $recipients
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Cashier\Subscription[] $subscriptions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCardBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCardLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereWordCredits($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Payment\CreditTransaction[] $creditTransactions
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
        'word_credits',
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
     * Adjust User credits.
     *
     * @param CreditTransactionType $type
     * @param int $amount
     * @return CreditTransaction|\Illuminate\Database\Eloquent\Model
     */
    public function adjustCredits(CreditTransactionType $type, $amount)
    {
        $this->word_credits += $amount;
        $this->save();

        $this->creditTransactions()->create([
            'amount' => $amount,
            'credit_transaction_type_id' => $type->id
        ]);
    }
}
