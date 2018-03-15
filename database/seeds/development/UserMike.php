<?php

use App\Payments\Plan;
use App\Payments\Subscription;
use App\User;
use Illuminate\Database\Seeder;

class UserMike extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \Stripe\Stripe::setApiKey("sk_test_WHtMmNZ34oYmb39Qep0uacDP");

        $token = \Stripe\Token::create([
            "card" => [
                "number" => "4242424242424242",
                "exp_month" => 3,
                "exp_year" => 2019,
                "cvc" => "314"
            ]
        ]);

        $stripeCustomer = \Stripe\Customer::create(array(
            "description" => "mail@wumike.com",
            "source" => $token->id
        ))->id;

        User::create([
            'name' => 'Mike Wu',
            'email' => 'mail@wumike.com',
            'password' => bcrypt('password'),
            'language_id' => 1,
            'stripe_id' => $stripeCustomer,
            'card_brand' => $token->card->brand,
            'card_last_four' => $token->card->last4
        ]);
    }
}
