<?php

use App\TranslationStatus;
use Illuminate\Database\Seeder;

class TranslationStatusesTableSeeder extends Seeder
{

    protected $statuses = [
        'pending',
        'translating',
        'complete'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->statuses as $status) {
            TranslationStatus::create([
                'description' => $status
            ]);
        }
    }
}
