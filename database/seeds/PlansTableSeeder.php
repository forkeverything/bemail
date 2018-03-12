<?php

use App\Payments\Plan;
use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{

    /**
     * Plans a User could be subscribed to.
     *
     * @var array
     */
    protected $plans = [
        [
            'name' => 'free',
            'cost' => 0,
            'surcharge' => 7
        ],
        [
            'name' => 'regular',
            'cost' => 1500,
            'surcharge' => 2
        ],
        [
            'name' => 'professional',
            'cost' => 4000,
            'surcharge' => 0
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->plans as $plan) {
            Plan::create([
                'name' => $plan['name'],
                'cost' => $plan['cost'],
                'surcharge' => $plan['surcharge']
            ]);
        }
    }
}
