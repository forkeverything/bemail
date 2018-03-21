<?php

use App\Translation\Recipient\RecipientType;
use Illuminate\Database\Seeder;

class RecipientTypesTableSeeder extends Seeder
{

    /**
     * Available types.
     *
     * @var array
     */
    protected $types = [
        'standard',
        'cc',
        'bcc'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->types as $type) {
            RecipientType::create([
                'name' => $type
            ]);
        }
    }
}
