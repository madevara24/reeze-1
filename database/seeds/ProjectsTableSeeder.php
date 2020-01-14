<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('projects')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('projects')->insert([
            [
                'name' => 'ausom',
                'repository' => 'ausom',
                'pic_id'=> 1,
                'description' => 'ausom',
                'sprint_duration' => 7,
                'sprint_start_day' => 1,
                'version' => '1.4.0',
                'created_at' => '2020-01-01 00:00:01'
            ],
            [
                'name' => 'FP Piranti Interaksi 2',
                'repository' => 'fp-piranti-interaksi-2',
                'pic_id'=> 5,
                'description' => 'Final project piranti interaksi 2',
                'sprint_duration' => 7,
                'sprint_start_day' => 1,
                'version' => '2.2.1',
                'created_at' => '2020-01-01 00:00:01'

            ],
            [
                'name' => 'Laundromat',
                'repository' => 'laundromat-app',
                'pic_id'=> 2,
                'description' => 'Aplikasi laundry online',
                'sprint_duration' => 7,
                'sprint_start_day' => 1,
                'version' => '1.0.5',
                'created_at' => '2020-01-01 00:00:01'

            ],
        ]);
    }
}
