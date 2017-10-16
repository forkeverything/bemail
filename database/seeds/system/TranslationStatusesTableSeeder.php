<?php

use App\TranslationStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationStatusesTableSeeder extends Seeder
{

    protected $statuses = [
        'pending',
        'translating',
        'complete',
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
            TranslationStatus::create([
                'description' => $status
            ]);
        }
    }
}
