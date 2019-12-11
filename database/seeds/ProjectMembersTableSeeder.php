<?php

use Illuminate\Database\Seeder;

class ProjectMembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('project_members')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('project_members')->insert([
            [
                'user_id' => 1,
                'project_id' => 1,
            ],
            [
                'user_id' => 1,
                'project_id' => 2,
            ],
            [
                'user_id' => 1,
                'project_id' => 3,
            ],
            [
                'user_id' => 2,
                'project_id' => 1,
            ],
            [
                'user_id' => 2,
                'project_id' => 3,
            ],
            [
                'user_id' => 3,
                'project_id' => 3,
            ],
            [
                'user_id' => 4,
                'project_id' => 2,
            ],
            [
                'user_id' => 5,
                'project_id' => 1,
            ],
            [
                'user_id' => 5,
                'project_id' => 2,
            ],
            [
                'user_id' => 7,
                'project_id' => 3,
            ],
            [
                'user_id' => 8,
                'project_id' => 2,
            ],
        ]);
    }
}
