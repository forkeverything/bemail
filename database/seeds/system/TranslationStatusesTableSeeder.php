<?php

use App\Translation\TranslationStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationStatusesTableSeeder extends Seeder
{

    protected $statuses = [
        'available',
        'pending',
        'approved',
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
