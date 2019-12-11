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
                'sprint_duration' => 14,
                'sprint_start_day' => 1,
            ],
            [
                'name' => 'fp-cacak',
                'repository' => 'fp-cacak',
                'pic_id'=> 5,
                'description' => 'fp-cacak',
                'sprint_duration' => 7,
                'sprint_start_day' => 1,
            ],
            [
                'name' => 'rental teman',
                'repository' => 'rental-teman',
                'pic_id'=> 2,
                'description' => 'rental-teman',
                'sprint_duration' => 7,
                'sprint_start_day' => 1,
            ],
        ]);
    }
}
