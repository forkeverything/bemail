<?php

use App\Translation\Order\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusesTableSeeder extends Seeder
{
    /**
     * Possible statuses.
     *
     * @var array
     */
    protected $statuses = [
        'available',
        'pending',
        'complete',
        'cancelled',
        'error'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->statuses as $status) {
            OrderStatus::create([
                'description' => $status
            ]);
        }
    }
}
