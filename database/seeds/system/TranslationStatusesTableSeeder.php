<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            DB::table('translation_statuses')->insert([
                'description' => $status
            ]);
        }
    }
}
