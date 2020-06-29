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
                'created_at' => '2020-06-22 09:04:01'
            ],
            [
                'card_id' => 1,
                'state' => 'planned',
                'created_at' => '2020-06-22 09:13:28'
            ],
            [
                'card_id' => 1,
                'state' => 'started',
                'created_at' => '2020-06-22 09:17:02'
            ],
            [
                'card_id' => 2,
                'state' => 'created',
                'created_at' => '2020-06-23 09:04:47'
            ],
            [
                'card_id' => 3,
                'state' => 'created',
                'created_at' => '2020-06-23 09:06:25'
            ],
            [
                'card_id' => 4,
                'state' => 'created',
                'created_at' => '2020-06-23 09:07:39'
            ],
            [
                'card_id' => 2,
                'state' => 'planned',
                'created_at' => '2020-06-23 09:13:55'
            ],
            [
                'card_id' => 3,
                'state' => 'planned',
                'created_at' => '2020-06-23 09:14:19'
            ],
            [
                'card_id' => 2,
                'state' => 'started',
                'created_at' => '2020-06-23 09:21:25'
            ],
            [
                'card_id' => 3,
                'state' => 'started',
                'created_at' => '2020-06-24 09:19:42'
            ],
            [
                'card_id' => 3,
                'state' => 'finished',
                'created_at' => '2020-06-24 14:17:37'
            ],
            [
                'card_id' => 3,
                'state' => 'accepted',
                'created_at' => '2020-06-24 14:56:14'
            ],
            [
                'card_id' => 1,
                'state' => 'finished',
                'created_at' => '2020-06-24 16:48:51'
            ],
            [
                'card_id' => 1,
                'state' => 'rejected',
                'created_at' => '2020-06-24 17:23:45'
            ],
            [
                'card_id' => 4,
                'state' => 'planned',
                'created_at' => '2020-06-25 09:14:31'
            ],
            [
                'card_id' => 1,
                'state' => 'started',
                'created_at' => '2020-06-25 09:16:33'
            ],
            [
                'card_id' => 2,
                'state' => 'finished',
                'created_at' => '2020-06-25 11:38:29'
            ],
            [
                'card_id' => 2,
                'state' => 'accepted',
                'created_at' => '2020-06-25 11:55:16'
            ],
            [
                'card_id' => 4,
                'state' => 'started',
                'created_at' => '2020-06-25 13:05:27'
            ],
            [
                'card_id' => 2,
                'state' => 'released',
                'created_at' => '2020-06-25 15:06:37'
            ],
            [
                'card_id' => 4,
                'state' => 'finished',
                'created_at' => '2020-06-25 15:58:21'
            ],
            [
                'card_id' => 4,
                'state' => 'accepted',
                'created_at' => '2020-06-25 16:12:44'
            ],
            [
                'card_id' => 3,
                'state' => 'released',
                'created_at' => '2020-06-25 17:08:54'
            ],
            [
                'card_id' => 4,
                'state' => 'released',
                'created_at' => '2020-06-25 17:11:07'
            ],
            [
                'card_id' => 1,
                'state' => 'finished',
                'created_at' => '2020-06-26 15:07:47'
            ],
            [
                'card_id' => 1,
                'state' => 'accepted',
                'created_at' => '2020-06-26 15:21:25'
            ],
            [
                'card_id' => 1,
                'state' => 'released',
                'created_at' => '2020-06-26 17:03:45'
            ],
            [
                'card_id' => 13,
                'state' => 'created',
                'created_at' => '2020-06-29 09:01:02'
            ],
            [
                'card_id' => 14,
                'state' => 'created',
                'created_at' => '2020-06-29 09:01:43'
            ],
            [
                'card_id' => 15,
                'state' => 'created',
                'created_at' => '2020-06-29 09:02:11'
            ],
            [
                'card_id' => 16,
                'state' => 'created',
                'created_at' => '2020-06-29 09:03:27'
            ],
            [
                'card_id' => 17,
                'state' => 'created',
                'created_at' => '2020-06-29 09:04:16'
            ],
            [
                'card_id' => 26,
                'state' => 'created',
                'created_at' => '2020-06-29 09:05:02'
            ],
            [
                'card_id' => 27,
                'state' => 'created',
                'created_at' => '2020-06-29 09:06:43'
            ],
            [
                'card_id' => 28,
                'state' => 'created',
                'created_at' => '2020-06-29 09:07:11'
            ],
            [
                'card_id' => 29,
                'state' => 'created',
                'created_at' => '2020-06-29 09:08:27'
            ],
            [
                'card_id' => 30,
                'state' => 'created',
                'created_at' => '2020-06-29 09:09:16'
            ],
            [
                'card_id' => 31,
                'state' => 'created',
                'created_at' => '2020-06-29 09:10:02'
            ],
            [
                'card_id' => 32,
                'state' => 'created',
                'created_at' => '2020-06-29 09:11:43'
            ],
            [
                'card_id' => 33,
                'state' => 'created',
                'created_at' => '2020-06-29 09:13:11'
            ],
            [
                'card_id' => 34,
                'state' => 'created',
                'created_at' => '2020-06-29 09:14:27'
            ],
            [
                'card_id' => 35,
                'state' => 'created',
                'created_at' => '2020-06-29 09:15:16'
            ],
            [
                'card_id' => 13,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:16:27'
            ],
            [
                'card_id' => 14,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:17:19'
            ],
            [
                'card_id' => 15,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:17:27'
            ],
            [
                'card_id' => 16,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:17:43'
            ],
            [
                'card_id' => 17,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:18:23'
            ],
            [
                'card_id' => 26,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:19:27'
            ],
            [
                'card_id' => 27,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:18:19'
            ],
            [
                'card_id' => 28,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:19:35'
            ],
            [
                'card_id' => 29,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:20:43'
            ],
            [
                'card_id' => 30,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:18:23'
            ],[
                'card_id' => 31,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:19:27'
            ],
            [
                'card_id' => 32,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:20:20'
            ],
            [
                'card_id' => 33,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:21:50'
            ],
            [
                'card_id' => 34,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:22:33'
            ],
            [
                'card_id' => 35,
                'state' => 'planned',
                'created_at' => '2020-06-29 09:23:01'
            ],
            [
                'card_id' => 13,
                'state' => 'started',
                'created_at' => '2020-06-29 09:27:12'
            ],
            [
                'card_id' => 14,
                'state' => 'started',
                'created_at' => '2020-06-29 09:31:05'
            ],
            [
                'card_id' => 17,
                'state' => 'started',
                'created_at' => '2020-06-29 09:57:51'
            ],
            [
                'card_id' => 14,
                'state' => 'finished',
                'created_at' => '2020-06-29 14:27:15'
            ],
            [
                'card_id' => 15,
                'state' => 'started',
                'created_at' => '2020-06-29 14:53:28'
            ],
            [
                'card_id' => 13,
                'state' => 'finished',
                'created_at' => '2020-06-29 16:09:45'
            ],
            [
                'card_id' => 17,
                'state' => 'finished',
                'created_at' => '2020-06-29 16:33:12'
            ],
            [
                'card_id' => 14,
                'state' => 'rejected',
                'created_at' => '2020-06-29 16:36:27'
            ],
            [
                'card_id' => 13,
                'state' => 'accepted',
                'created_at' => '2020-06-29 16:39:54'
            ],
            [
                'card_id' => 17,
                'state' => 'accepted',
                'created_at' => '2020-06-29 16:40:21'
            ],
            [
                'card_id' => 16,
                'state' => 'started',
                'created_at' => '2020-06-30 09:36:43'
            ],
            [
                'card_id' => 31,
                'state' => 'started',
                'created_at' => '2020-06-30 09:47:56'
            ],
            [
                'card_id' => 15,
                'state' => 'finished',
                'created_at' => '2020-06-30 10:44:00'
            ],
            [
                'card_id' => 14,
                'state' => 'started',
                'created_at' => '2020-06-30 11:01:43'
            ],
            [
                'card_id' => 16,
                'state' => 'finished',
                'created_at' => '2020-06-30 15:56:12'
            ],
            [
                'card_id' => 14,
                'state' => 'finished',
                'created_at' => '2020-06-30 16:01:20'
            ],
            [
                'card_id' => 16,
                'state' => 'accepted',
                'created_at' => '2020-06-30 16:05:12'
            ],
            [
                'card_id' => 14,
                'state' => 'accepted',
                'created_at' => '2020-06-30 16:09:20'
            ],
            [
                'card_id' => 15,
                'state' => 'accepted',
                'created_at' => '2020-06-30 16:14:00'
            ],
            [
                'card_id' => 26,
                'state' => 'started',
                'created_at' => '2020-07-01 09:29:37'
            ],
            [
                'card_id' => 28,
                'state' => 'started',
                'created_at' => '2020-07-01 09:29:37'
            ],
            [
                'card_id' => 28,
                'state' => 'finished',
                'created_at' => '2020-07-01 12:37:38'
            ],
            [
                'card_id' => 29,
                'state' => 'started',
                'created_at' => '2020-07-01 12:57:45'
            ],
            [
                'card_id' => 26,
                'state' => 'finished',
                'created_at' => '2020-07-01 15:13:25'
            ],
            [
                'card_id' => 29,
                'state' => 'finished',
                'created_at' => '2020-07-01 15:31:54'
            ],
            [
                'card_id' => 31,
                'state' => 'finished',
                'created_at' => '2020-07-01 15:40:41'
            ],
            [
                'card_id' => 26,
                'state' => 'accepted',
                'created_at' => '2020-07-01 16:03:25'
            ],
            [
                'card_id' => 28,
                'state' => 'accepted',
                'created_at' => '2020-07-01 16:07:38'
            ],
            [
                'card_id' => 29,
                'state' => 'accepted',
                'created_at' => '2020-07-01 16:11:54'
            ],
            [
                'card_id' => 31,
                'state' => 'rejected',
                'created_at' => '2020-07-01 16:14:05'
            ],
            [
                'card_id' => 27,
                'state' => 'started',
                'created_at' => '2020-07-02 09:05:44'
            ],
            [
                'card_id' => 34,
                'state' => 'started',
                'created_at' => '2020-07-02 09:21:25'
            ],
            [
                'card_id' => 31,
                'state' => 'started',
                'created_at' => '2020-07-02 09:32:35'
            ],
            [
                'card_id' => 34,
                'state' => 'finished',
                'created_at' => '2020-07-02 15:53:20'
            ],
            [
                'card_id' => 27,
                'state' => 'finished',
                'created_at' => '2020-07-02 16:03:04'
            ],
            [
                'card_id' => 31,
                'state' => 'finished',
                'created_at' => '2020-07-02 16:16:26'
            ],
            [
                'card_id' => 34,
                'state' => 'accepted',
                'created_at' => '2020-07-02 16:23:20'
            ],
            [
                'card_id' => 27,
                'state' => 'accepted',
                'created_at' => '2020-07-02 16:30:04'
            ],
            [
                'card_id' => 31,
                'state' => 'accepted',
                'created_at' => '2020-07-02 16:36:43'
            ],
            [
                'card_id' => 32,
                'state' => 'started',
                'created_at' => '2020-07-03 09:20:58'
            ],
            [
                'card_id' => 35,
                'state' => 'started',
                'created_at' => '2020-07-03 09:27:24'
            ],
            [
                'card_id' => 32,
                'state' => 'finished',
                'created_at' => '2020-07-03 11:34:45'
            ],
            [
                'card_id' => 33,
                'state' => 'started',
                'created_at' => '2020-07-03 12:04:55'
            ],
            [
                'card_id' => 33,
                'state' => 'finished',
                'created_at' => '2020-07-03 15:30:29'
            ],
            [
                'card_id' => 35,
                'state' => 'finished',
                'created_at' => '2020-07-03 15:39:20'
            ],
            [
                'card_id' => 32,
                'state' => 'accepted',
                'created_at' => '2020-07-03 15:44:45'
            ],
            [
                'card_id' => 33,
                'state' => 'accepted',
                'created_at' => '2020-07-03 15:50:29'
            ],
            [
                'card_id' => 35,
                'state' => 'accepted',
                'created_at' => '2020-07-03 15:59:20'
            ],
            [
                'card_id' => 13,
                'state' => 'released',
                'created_at' => '2020-07-03 16:10:09'
            ],
            [
                'card_id' => 14,
                'state' => 'released',
                'created_at' => '2020-07-03 16:13:45'
            ],
            [
                'card_id' => 15,
                'state' => 'released',
                'created_at' => '2020-07-03 16:16:28'
            ],
            [
                'card_id' => 16,
                'state' => 'released',
                'created_at' => '2020-07-03 16:19:54'
            ],
            [
                'card_id' => 17,
                'state' => 'released',
                'created_at' => '2020-07-03 16:21:40'
            ],
            [
                'card_id' => 26,
                'state' => 'released',
                'created_at' => '2020-07-03 16:10:09'
            ],
            [
                'card_id' => 27,
                'state' => 'released',
                'created_at' => '2020-07-03 16:13:45'
            ],
            [
                'card_id' => 28,
                'state' => 'released',
                'created_at' => '2020-07-03 16:16:28'
            ],
            [
                'card_id' => 29,
                'state' => 'released',
                'created_at' => '2020-07-03 16:19:54'
            ],
            [
                'card_id' => 30,
                'state' => 'released',
                'created_at' => '2020-07-03 16:21:40'
            ],
            [
                'card_id' => 31,
                'state' => 'released',
                'created_at' => '2020-07-03 16:23:33'
            ],
            [
                'card_id' => 32,
                'state' => 'released',
                'created_at' => '2020-07-03 16:25:19'
            ],
            [
                'card_id' => 33,
                'state' => 'released',
                'created_at' => '2020-07-03 16:27:38'
            ],
            [
                'card_id' => 34,
                'state' => 'released',
                'created_at' => '2020-07-03 16:30:04'
            ],
            [
                'card_id' => 35,
                'state' => 'released',
                'created_at' => '2020-07-03 16:32:46'
            ],
        ]);
    }
}
