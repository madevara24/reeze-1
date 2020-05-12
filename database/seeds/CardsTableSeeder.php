<?php

use Illuminate\Database\Seeder;

class CardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cards')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('cards')->insert([
            [
                'project_id' => 1,
                'owner' => 1,
                'requester' => 1,
                'title' => 'login',
                'github_branch_name' => 'feature/login',
                'description' => 'login',
                'points' => '3',
                'type' =>'feature',
            ],
            [
                'project_id' => 1,
                'owner' => 2,
                'requester' => 2,
                'title' => 'login gagal',
                'github_branch_name' => 'bug/login',
                'description' => 'login gagal',
                'points' => '5',
                
                'type' => 'bug'
            ],
            [
                'project_id' => 1,
                'owner' => 5,
                'requester' => 1,
                'title' => 'Make search bar on projects page to search for project by name',
                'github_branch_name' => 'feature/make-search-bar',
                'description' => 'Make search bar on projects page to search for project by name',
                'points' => '3',
                
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 2,
                'requester' => 1,
                'title' => 'Make CRUD query for cards',
                'github_branch_name' => 'feature/make-card-query',
                'description' => 'Make CRUD query for cards',
                'points' => '3',
                
                'type' => 'feature'
            ],
            [
                'project_id' => 2,
                'owner' => 4,
                'requester' => 5,
                'title' => 'Make the lobby interface/menu',
                'github_branch_name' => 'feature/make-lobby-interface',
                'description' => 'Make the lobby interface/menu',
                'points' => '1',
                
                'type' => 'feature'
            ],
            [
                'project_id' => 2,
                'owner' => 5,
                'requester' => 5,
                'title' => 'Make UI assets for menu',
                'github_branch_name' => 'feature/make-ui-assets',
                'description' => 'Make UI assets for menu',
                'points' => '3',
                
                'type' => 'feature'
            ],
            [
                'project_id' => 2,
                'owner' => 8,
                'requester' => 5,
                'title' => 'Fix player collision on gameplay',
                'github_branch_name' => 'bug/fix-collision',
                'description' => 'Fix player collision on gameplay',
                'points' => '3',
                
                'type' => 'bug'
            ],
            [
                'project_id' => 2,
                'owner' => 1,
                'requester' => 5,
                'title' => 'Make multiplayer connection using unet',
                'github_branch_name' => 'feature/make-multiplayer-connection',
                'description' => 'Make multiplayer connection using unet',
                'points' => '5',
                
                'type' => 'feature'
            ],
            [
                'project_id' => 3,
                'owner' => 1,
                'requester' => 1,
                'title' => 'Fix login flow',
                'github_branch_name' => 'bug/fix-auth',
                'description' => 'Fix login flow',
                'points' =>'3',
                
                'type' => 'bug'
            ],
            [
                'project_id' => 3,
                'owner' => 2,
                'requester' => 2,
                'title' => 'Make query for orders feature',
                'github_branch_name' => 'feature/make-orders-query',
                'description' => 'Make query for orders feature',
                'points' => '3',
                
                'type' => 'feature'
            ],
            [
                'project_id' => 3,
                'owner' => 3,
                'requester' => 2,
                'title' => 'Make mobile views',
                'github_branch_name' => 'feature/make-mobile-views',
                'description' => 'Make mobile views',
                'points' => '5',
                
                'type' => 'feature'
            ],
            [
                'project_id' => 3,
                'owner' => 7,
                'requester' => 2,
                'title' => 'Research google maps location identifier',
                'github_branch_name' => 'feature/research-gmaps-location',
                'description' => 'Research google maps location identifier',
                'points' => '8',
                
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 2,
                'requester' => 1,
                'title' => 'Fix project member query keeps showing non-member',
                'github_branch_name' => 'bug/fix-project-members-query',
                'description' => 'Fix project member query keeps showing non-member',
                'points' => '3',
                'type' => 'bug'
            ],
            [
                'project_id' => 1,
                'owner' => 5,
                'requester' => 1,
                'title' => 'Make ui design for login page',
                'github_branch_name' => 'feature/make-login-UI',
                'description' => 'Make ui design for login page',
                'points' => '1',
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 5,
                'requester' => 1,
                'title' => 'Add color code to cards based on card types',
                'github_branch_name' => 'feature/add-card-color-code',
                'description' => 'Add color code to cards based on card types',
                'points' => '1',
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 1,
                'requester' => 1,
                'title' => 'Make api for cards based on query',
                'github_branch_name' => 'feature/make-cards-api',
                'description' => 'Make api for cards based on query',
                'points' => '3',
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 2,
                'requester' => 1,
                'title' => 'Make query for card log table',
                'github_branch_name' => 'feature/make-card-log-query',
                'description' => 'Make query for card log table',
                'points' => '3',
                'type' => 'feature'
            ],
            [
                'project_id' => 2,
                'owner' => 8,
                'requester' => 5,
                'title' => 'Change the damage to be dynamic based on RNG',
                'github_branch_name' => 'feature/make-dynamic-damage',
                'description' => 'Change the damage to be dynamic based on RNG',
                'points' => '1',
                'type' => 'feature'
            ],
            [
                'project_id' => 2,
                'owner' => 4,
                'requester' => 5,
                'title' => 'Research and make player design/attribute',
                'github_branch_name' => 'feature/research-player-type',
                'description' => 'Research and make player design/attribute',
                'points' => '5',
                'type' => 'feature'
            ],
            [
                'project_id' => 2,
                'owner' => 1,
                'requester' => 1,
                'title' => 'Fix back button on lobby not working',
                'github_branch_name' => 'bug/fix-lobby-navigation',
                'description' => 'Fix back button on lobby not working',
                'points' => '1',
                'type' => 'bug'
            ],
            [
                'project_id' => 2,
                'owner' => 1,
                'requester' => 5,
                'title' => 'Research steam api',
                'github_branch_name' => 'feature/research-steam-api',
                'description' => 'Research steam api',
                'points' => '8',
                'type' => 'feature'
            ],
            [
                'project_id' => 2,
                'owner' => 5,
                'requester' => 5,
                'title' => 'Make assets for gameplay environment',
                'github_branch_name' => 'feature/make-environment-asset',
                'description' => 'Make assets for gameplay environment',
                'points' => '5',
                'type' => 'feature'
            ],
            [
                'project_id' => 3,
                'owner' => 7,
                'requester' => 7,
                'title' => 'Fix order flow not searching from near location',
                'github_branch_name' => 'bug/fix-order-flow',
                'description' => 'Fix order flow not searching from near location',
                'points' => '3',
                'type' => 'bug'
            ],
            [
                'project_id' => 3,
                'owner' => 2,
                'requester' => 2,
                'title' => 'Make api for catalog page',
                'github_branch_name' => 'feature/make-catalog-api',
                'description' => 'Make api for catalog page',
                'points' => '3',
                'type' => 'feature'
            ],
            [
                'project_id' => 3,
                'owner' => 1,
                'requester' => 2,
                'title' => 'Make api for payment with midtrans',
                'github_branch_name' => 'feature/make-payment-api',
                'description' => 'Make api for payment with midtrans',
                'points' => '5',
                'type' => 'feature'
            ]
        ]);
    }
}
