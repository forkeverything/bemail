<?php

use Illuminate\Database\Seeder;

class SysDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LanguagesTableSeeder::class);
        $this->call(TranslationStatusesTableSeeder::class);
        $this->call(RecipientTypesTableSeeder::class);
        $this->call(CreditTransactionTypesTableSeeder::class);
    }
}
