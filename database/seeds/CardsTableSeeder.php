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
                'title' => 'Set Up Project',
                'github_branch_name' => 'feature/set-up',
                'description' => 'Set up project environment and boilerplate code',
                'points' => '3',
                'type' =>'feature',
            ],
            [
                'project_id' => 1,
                'owner' => 2,
                'requester' => 2,
                'title' => 'Research DB Connection',
                'github_branch_name' => 'feature/set-up-db-connection',
                'description' => 'Research DB connection with both relational and non relational DB',
                'points' => '5',
                
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 5,
                'requester' => 1,
                'title' => 'Interview Client for Product Overview',
                'github_branch_name' => 'feature/client-interview',
                'description' => 'Interview client for product overview and make the coresponding document',
                'points' => '3',
                
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 2,
                'requester' => 1,
                'title' => 'Make Auth',
                'github_branch_name' => 'feature/make-auth',
                'description' => 'Make authentication system',
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
                'title' => 'Fix DB Connection for MongoDB',
                'github_branch_name' => 'bug/fix-mongodb-connecion',
                'description' => 'Fix MongoDB connection cannot connect after authentication',
                'points' => '3',
                'type' => 'bug'
            ],
            [
                'project_id' => 1,
                'owner' => 5,
                'requester' => 1,
                'title' => 'Make UI Design for Login Page',
                'github_branch_name' => 'feature/make-login-ui',
                'description' => 'Make ui design for login page',
                'points' => '1',
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 5,
                'requester' => 1,
                'title' => 'Make UI Design for Homepage',
                'github_branch_name' => 'feature/make-homepage-ui',
                'description' => 'Make UI design for homepage',
                'points' => '1',
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 2,
                'requester' => 1,
                'title' => 'Set Up Web Routes',
                'github_branch_name' => 'feature/set-up-web-routes',
                'description' => 'Set up web routes for navigations',
                'points' => '3',
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 1,
                'requester' => 1,
                'title' => 'Make Biome Creation Feature',
                'github_branch_name' => 'feature/make-biome-creation-feature',
                'description' => 'Make biome creation feature',
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
            ],
            [
                'project_id' => 1,
                'owner' => 2,
                'requester' => 1,
                'title' => 'Make Setting Page',
                'github_branch_name' => 'feature/make-setting-page',
                'description' => 'Make setting page for user',
                'points' => '3',
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 2,
                'requester' => 2,
                'title' => 'Fix Bug Google Login',
                'github_branch_name' => 'bug/google-login',
                'description' => 'Fix cannot authenticate with google login',
                'points' => '3',
                'type' => 'bug'
            ],
            [
                'project_id' => 1,
                'owner' => 5,
                'requester' => 1,
                'title' => 'Make UI Design for Biome Creation Page',
                'github_branch_name' => 'feature/make-biome-creation-ui',
                'description' => 'Make ui design for biome creation page',
                'points' => '1',
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 5,
                'requester' => 1,
                'title' => 'Make UI Design for Biome Cards',
                'github_branch_name' => 'feature/make-biome-creation-cards-ui',
                'description' => 'Make ui design for biome cards',
                'points' => '1',
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 5,
                'requester' => 1,
                'title' => 'Make UI Design for Setting Page',
                'github_branch_name' => 'feature/make-setting-page-ui',
                'description' => 'Make ui design for setting page',
                'points' => '1',
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 1,
                'requester' => 1,
                'title' => 'Make Biome Lifecycle Feature',
                'github_branch_name' => 'feature/make-biome-lifecycle-feature',
                'description' => 'Make biome lifecycle feature',
                'points' => '3',
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 2,
                'requester' => 1,
                'title' => 'Make Homepage',
                'github_branch_name' => 'feature/make-homepage',
                'description' => 'Make homepage',
                'points' => '1',
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 2,
                'requester' => 1,
                'title' => 'Make Navigation System',
                'github_branch_name' => 'feature/make-navigation',
                'description' => 'Make navigation',
                'points' => '1',
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 5,
                'requester' => 1,
                'title' => 'Make UI Design for Navigation',
                'github_branch_name' => 'feature/make-navigation-ui',
                'description' => 'Make UI design for navbar and sidebar',
                'points' => '1',
                'type' => 'feature'
            ],
            [
                'project_id' => 1,
                'owner' => 1,
                'requester' => 1,
                'title' => 'Make Biome Alteration Feature',
                'github_branch_name' => 'feature/make-biome-alteration-feature',
                'description' => 'Make biome alteration feature',
                'points' => '3',
                'type' => 'feature'
            ],
        ]);
    }
}
