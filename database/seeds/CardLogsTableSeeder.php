<?php

use Illuminate\Database\Seeder;

class CardLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('card_logs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('card_logs')->insert([
            [
                'card_id' => 1,
                'state' => 'created',
                'created_at' => '2019-12-09 09:04:01'
            ],
            [
                'card_id' => 2,
                'state' => 'created',
                'created_at' => '2019-12-09 09:04:47'
            ],
            [
                'card_id' => 3,
                'state' => 'created',
                'created_at' => '2019-12-09 09:06:25'
            ],
            [
                'card_id' => 4,
                'state' => 'created',
                'created_at' => '2019-12-09 09:07:39'
            ],
            [
                'card_id' => 1,
                'state' => 'planned',
                'created_at' => '2019-12-09 09:13:28'
            ],
            [
                'card_id' => 2,
                'state' => 'planned',
                'created_at' => '2019-12-09 09:13:55'
            ],
            [
                'card_id' => 3,
                'state' => 'planned',
                'created_at' => '2019-12-09 09:14:19'
            ],
            [
                'card_id' => 1,
                'state' => 'started',
                'created_at' => '2019-12-09 09:17:02'
            ],
            [
                'card_id' => 2,
                'state' => 'started',
                'created_at' => '2019-12-09 09:21:25'
            ],
            [
                'card_id' => 3,
                'state' => 'started',
                'created_at' => '2019-12-09 09:19:42'
            ],
            [
                'card_id' => 3,
                'state' => 'finished',
                'created_at' => '2019-12-09 14:17:37'
            ],
            [
                'card_id' => 3,
                'state' => 'accepted',
                'created_at' => '2019-12-09 14:56:14'
            ],
            [
                'card_id' => 1,
                'state' => 'finished',
                'created_at' => '2019-12-09 16:48:51'
            ],
            [
                'card_id' => 1,
                'state' => 'rejected',
                'created_at' => '2019-12-09 17:23:45'
            ],
            [
                'card_id' => 4,
                'state' => 'planned',
                'created_at' => '2019-12-10 09:14:31'
            ],
            [
                'card_id' => 1,
                'state' => 'started',
                'created_at' => '2019-12-10 09:16:33'
            ],
            [
                'card_id' => 2,
                'state' => 'finished',
                'created_at' => '2019-12-10 11:38:29'
            ],
            [
                'card_id' => 2,
                'state' => 'accepted',
                'created_at' => '2019-12-10 11:55:16'
            ],
            [
                'card_id' => 4,
                'state' => 'started',
                'created_at' => '2019-12-10 13:05:27'
            ],
            [
                'card_id' => 2,
                'state' => 'released',
                'created_at' => '2019-12-10 15:06:37'
            ],
            [
                'card_id' => 1,
                'state' => 'finished',
                'created_at' => '2019-12-10 15:07:47'
            ],
            [
                'card_id' => 1,
                'state' => 'accepted',
                'created_at' => '2019-12-10 15:21:25'
            ],
            [
                'card_id' => 4,
                'state' => 'finished',
                'created_at' => '2019-12-10 15:58:21'
            ],
            [
                'card_id' => 4,
                'state' => 'accepted',
                'created_at' => '2019-12-10 16:12:44'
            ],
            [
                'card_id' => 1,
                'state' => 'released',
                'created_at' => '2019-12-10 17:03:45'
            ],
            [
                'card_id' => 3,
                'state' => 'released',
                'created_at' => '2019-12-11 17:08:54'
            ],
            [
                'card_id' => 4,
                'state' => 'released',
                'created_at' => '2019-12-13 17:11:07'
            ],
        ]);
    }
}
