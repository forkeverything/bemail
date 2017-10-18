<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesTableSeeder extends Seeder
{
    private $languages = [
        'en' => 'English',
        'zh' => 'Chinese',
        'ja' => 'Japanese',
        'id' => 'Indonesian'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->languages as $code => $name) {
            DB::table('languages')->insert([
                'code' => $code,
                'name' => $name
            ]);

        }
    }
}
