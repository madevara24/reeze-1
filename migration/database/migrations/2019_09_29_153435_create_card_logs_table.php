<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('card_id');
            $table->foreign('card_id')
                ->references('id')
                ->on('cards')
                ->onDelete('cascade');

            $table->dateTime('started_at');
            $table->dateTime('finished_at');
            $table->dateTime('released_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_logs');
    }
}
