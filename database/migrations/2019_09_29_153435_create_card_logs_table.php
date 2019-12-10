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

            $table->enum('state', ['created', 'started', 'finished', 'released', 'rejected', 'planned', 'accepted']);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
