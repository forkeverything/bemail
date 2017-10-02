<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $this->command->info('----- START SEEDING SYS DATA -----');
        $this->call(SysDataSeeder::class);
        $this->command->info('----- FINSH SEEDING SYS DATA -----');
        $this->command->info('----- START SEEDING DEV DATA -----');
        $this->call(DevDataSeeder::class);
        $this->command->info('----- FINSH SEEDING DEV DATA -----');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Model::reguard();
    }
}
