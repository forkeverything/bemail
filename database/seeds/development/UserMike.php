<?php

use App\User;
use Illuminate\Database\Seeder;

class UserMike extends Seeder
{

    /**
     * Add Credit Card to Stripe?
     *
     * @var bool
     */
    protected $withCreditCard = false;

    /**
     * @var null
     */
    protected $stripeCustomerId = null;
    /**
     * @var null
     */
    protected $cardBrand = null;
    /**
     * @var null
     */
    protected $cardLastFour = null;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if($this->withCreditCard) $this->setStripeDetails();

        User::create([
            'name' => 'Mike Wu',
            'email' => 'mail@wumike.com',
            'password' => bcrypt('password'),
            'language_id' => 1,
            'stripe_id' => $this->stripeCustomerId,
            'card_brand' => $this->cardBrand,
            'card_last_four' => $this->cardLastFour
        ]);
    }

    /**
     * Ready to make API calls
     */
    private function setStripeApi()
    {
        \Stripe\Stripe::setApiKey("sk_test_WHtMmNZ34oYmb39Qep0uacDP");
        return $this;
    }

    /**
     * Credit Card token from Stripe.
     *
     * @return \Stripe\Token
     */
    private function stripeCardToken()
    {
        return \Stripe\Token::create([
            "card" => [
                "number" => "4242424242424242",
                "exp_month" => 3,
                "exp_year" => 2019,
                "cvc" => "314"
            ]
        ]);
    }

    /**
     * Stripe Customer ID
     *
     * @param \Stripe\Token $token
     * @return string
     */
    private function stripeCustomerId(\Stripe\Token $token)
    {
        return \Stripe\Customer::create(array(
            "description" => "mail@wumike.com",
            "source" => $token->id
        ))->id;
    }

    /**
     * Set Stripe details to save to User.
     */
    private function setStripeDetails()
    {
        $this->setStripeApi();
        $token = $this->stripeCardToken();
        $this->stripeCustomerId = $this->stripeCustomerId($token);
        $this->cardBrand =$token->card->brand;
        $this->cardLastFour = $token->card->last4;
    }

}
