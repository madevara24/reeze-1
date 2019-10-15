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
        DB::table('projects')->insert([
            [
                'name' => 'ausom',
                'repository' => 'ausom',
                'description' => 'ausom',
                'sprint_duration' => 14,
                'sprint_start_day' => 1,
            ],
            [
                'name' => 'fp-cacak',
                'repository' => 'fp-cacak',
                'description' => 'fp-cacak',
                'sprint_duration' => 7,
                'sprint_start_day' => 1,
            ],
            [
                'name' => 'rental teman',
                'repository' => 'rental-teman',
                'description' => 'rental-teman',
                'sprint_duration' => 7,
                'sprint_start_day' => 1,
            ],
        ]);
    }
}
