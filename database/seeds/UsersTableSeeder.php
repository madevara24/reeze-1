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
