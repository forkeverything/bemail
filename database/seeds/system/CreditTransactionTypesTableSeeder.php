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
            'name' => 'invite',
            'description' => 'Accepted invitation to join.'
        ],
        [
            'name' => 'host',
            'description' => 'Invited a friend to join.'
        ],
        [
            'name' => 'manual',
            'description' => 'Manually added credit.'
        ],
        [
            'name' => 'manual',
            'description' => 'Manually deducted credit.'
        ],
        [
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
                'name' => $type['name'],
                'description' => $type['description']
            ]);
        }
    }
}
