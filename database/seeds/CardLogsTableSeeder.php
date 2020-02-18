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
                'created_at' => '2020-02-03 09:04:01'
            ],
            [
                'card_id' => 1,
                'state' => 'planned',
                'created_at' => '2020-02-03 09:13:28'
            ],
            [
                'card_id' => 1,
                'state' => 'started',
                'created_at' => '2020-02-03 09:17:02'
            ],
            [
                'card_id' => 2,
                'state' => 'created',
                'created_at' => '2020-02-04 09:04:47'
            ],
            [
                'card_id' => 3,
                'state' => 'created',
                'created_at' => '2020-02-04 09:06:25'
            ],
            [
                'card_id' => 4,
                'state' => 'created',
                'created_at' => '2020-02-04 09:07:39'
            ],
            [
                'card_id' => 2,
                'state' => 'planned',
                'created_at' => '2020-02-04 09:13:55'
            ],
            [
                'card_id' => 3,
                'state' => 'planned',
                'created_at' => '2020-02-04 09:14:19'
            ],
            [
                'card_id' => 2,
                'state' => 'started',
                'created_at' => '2020-02-04 09:21:25'
            ],
            [
                'card_id' => 3,
                'state' => 'started',
                'created_at' => '2020-02-5 09:19:42'
            ],
            [
                'card_id' => 3,
                'state' => 'finished',
                'created_at' => '2020-02-5 14:17:37'
            ],
            [
                'card_id' => 3,
                'state' => 'accepted',
                'created_at' => '2020-02-5 14:56:14'
            ],
            [
                'card_id' => 1,
                'state' => 'finished',
                'created_at' => '2020-02-5 16:48:51'
            ],
            [
                'card_id' => 1,
                'state' => 'rejected',
                'created_at' => '2020-02-5 17:23:45'
            ],
            [
                'card_id' => 4,
                'state' => 'planned',
                'created_at' => '2020-02-06 09:14:31'
            ],
            [
                'card_id' => 1,
                'state' => 'started',
                'created_at' => '2020-02-06 09:16:33'
            ],
            [
                'card_id' => 2,
                'state' => 'finished',
                'created_at' => '2020-02-06 11:38:29'
            ],
            [
                'card_id' => 2,
                'state' => 'accepted',
                'created_at' => '2020-02-06 11:55:16'
            ],
            [
                'card_id' => 4,
                'state' => 'started',
                'created_at' => '2020-02-06 13:05:27'
            ],
            [
                'card_id' => 2,
                'state' => 'released',
                'created_at' => '2020-02-06 15:06:37'
            ],
            [
                'card_id' => 4,
                'state' => 'finished',
                'created_at' => '2020-02-06 15:58:21'
            ],
            [
                'card_id' => 4,
                'state' => 'accepted',
                'created_at' => '2020-02-06 16:12:44'
            ],
            [
                'card_id' => 3,
                'state' => 'released',
                'created_at' => '2020-02-06 17:08:54'
            ],
            [
                'card_id' => 4,
                'state' => 'released',
                'created_at' => '2020-02-06 17:11:07'
            ],
            [
                'card_id' => 1,
                'state' => 'finished',
                'created_at' => '2020-02-07 15:07:47'
            ],
            [
                'card_id' => 1,
                'state' => 'accepted',
                'created_at' => '2020-02-07 15:21:25'
            ],
            [
                'card_id' => 1,
                'state' => 'released',
                'created_at' => '2020-02-07 17:03:45'
            ],
            [
                'card_id' => 13,
                'state' => 'created',
                'created_at' => '2020-02-10 09:01:02'
            ],
            [
                'card_id' => 14,
                'state' => 'created',
                'created_at' => '2020-02-10 09:01:43'
            ],
            [
                'card_id' => 15,
                'state' => 'created',
                'created_at' => '2020-02-10 09:02:11'
            ],
            [
                'card_id' => 16,
                'state' => 'created',
                'created_at' => '2020-02-10 09:03:27'
            ],
            [
                'card_id' => 17,
                'state' => 'created',
                'created_at' => '2020-02-10 09:04:16'
            ],
            [
                'card_id' => 13,
                'state' => 'planned',
                'created_at' => '2020-02-10 09:16:27'
            ],
            [
                'card_id' => 14,
                'state' => 'planned',
                'created_at' => '2020-02-10 09:17:19'
            ],
            [
                'card_id' => 15,
                'state' => 'planned',
                'created_at' => '2020-02-10 09:17:27'
            ],
            [
                'card_id' => 16,
                'state' => 'planned',
                'created_at' => '2020-02-10 09:17:43'
            ],
            [
                'card_id' => 17,
                'state' => 'planned',
                'created_at' => '2020-02-10 09:18:10'
            ],
            [
                'card_id' => 13,
                'state' => 'started',
                'created_at' => '2020-02-10 09:25:12'
            ],
            [
                'card_id' => 14,
                'state' => 'started',
                'created_at' => '2020-02-10 09:31:05'
            ],
            [
                'card_id' => 16,
                'state' => 'started',
                'created_at' => '2020-02-10 09:57:51'
            ],
            [
                'card_id' => 14,
                'state' => 'finished',
                'created_at' => '2020-02-10 13:15:21'
            ],
            [
                'card_id' => 14,
                'state' => 'rejected',
                'created_at' => '2020-02-10 13:23:58'
            ],
            [
                'card_id' => 14,
                'state' => 'started',
                'created_at' => '2020-02-10 13:29:04'
            ],
            [
                'card_id' => 14,
                'state' => 'finished',
                'created_at' => '2020-02-10 15:05:21'
            ],
            [
                'card_id' => 14,
                'state' => 'accepted',
                'created_at' => '2020-02-10 15:07:49'
            ],
            [
                'card_id' => 15,
                'state' => 'started',
                'created_at' => '2020-02-10 15:36:18'
            ],
            [
                'card_id' => 14,
                'state' => 'released',
                'created_at' => '2020-02-10 15:48:50'
            ],
            [
                'card_id' => 16,
                'state' => 'finished',
                'created_at' => '2020-02-10 16:52:22'
            ],
            [
                'card_id' => 13,
                'state' => 'finished',
                'created_at' => '2020-02-10 16:56:31'
            ],
            [
                'card_id' => 16,
                'state' => 'accepted',
                'created_at' => '2020-02-10 17:00:52'
            ],
            [
                'card_id' => 15,
                'state' => 'finished',
                'created_at' => '2020-02-10 17:01:04'
            ],
            [
                'card_id' => 13,
                'state' => 'accepted',
                'created_at' => '2020-02-10 17:04:37'
            ],
            [
                'card_id' => 15,
                'state' => 'accepted',
                'created_at' => '2020-02-10 17:08:12'
            ],
            [
                'card_id' => 13,
                'state' => 'released',
                'created_at' => '2020-02-10 17:12:38'
            ],
            [
                'card_id' => 15,
                'state' => 'released',
                'created_at' => '2020-02-10 17:12:46'
            ],
            [
                'card_id' => 16,
                'state' => 'released',
                'created_at' => '2020-02-10 17:12:51
                '
            ],
            [
                'card_id' => 17,
                'state' => 'started',
                'created_at' => '2020-02-11 09:17:40'
            ],
        ]);
    }
}
