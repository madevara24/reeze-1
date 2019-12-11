<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('users')->insert([
            ['name' => 'zainokta'],
            ['name' => 'madevara24'],
            ['name' => 'subosko'],
            ['name' => 'sendiki'],
            ['name' => 'masjul'],
            ['name' => 'surobit'],
            ['name' => 'saipul'],
            ['name' => 'gay_depok'],
            ]
        );
    }
}
