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
        DB::table('users')->insert([
            ['username' => 'zainokta'],
            ['username' => 'madevara24'],
            ['username' => 'subosko'],
            ['username' => 'sendiki'],
            ['username' => 'masjul'],
            ['username' => 'surobit'],
            ['username' => 'saipul'],
            ['username' => 'gay_depok'],
            ]
        );
    }
}
