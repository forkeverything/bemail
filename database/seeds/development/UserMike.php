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
        User::create([
            'name' => 'Mike Wu',
            'email' => 'mail@wumike.com',
            'password' => bcrypt('password'),
            'language_id' => 1
        ]);
    }
}
