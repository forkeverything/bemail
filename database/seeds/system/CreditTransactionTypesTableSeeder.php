<?php

use App\Payments\CreditTransactionType;
use Illuminate\Database\Seeder;

class CreditTransactionTypesTableSeeder extends Seeder
{

    /**
     * Possible types of credit transactions.
     *
     * @var array
     */
    protected $types = [
        [
            'add' => 1,
            'name' => 'invite',
            'description' => 'Accepted invitation to join.'
        ],
        [
            'add' => 1,
            'name' => 'host',
            'description' => 'Invited a friend to join.'
        ],
        [
            'add' => 1,
            'name' => 'manual',
            'description' => 'Manually added credit.'
        ],
        [
            'add' => 0,
            'name' => 'manual',
            'description' => 'Manually deducted credit.'
        ],
        [
            'add' => 0,
            'name' => "payment",
            'description' => 'Paid for translated message.'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->types as $type) {
            CreditTransactionType::create([
                'add' => $type['add'],
                'name' => $type['name'],
                'description' => $type['description']
            ]);
        }
    }
}
