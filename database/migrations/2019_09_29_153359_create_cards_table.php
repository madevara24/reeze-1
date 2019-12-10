<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title');

            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade');

            $table->unsignedBigInteger('owner');
            $table->foreign('owner')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')->nullable();

            $table->unsignedBigInteger('requester');
            $table->foreign('requester')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->string('github_branch_name')->nullable();
            $table->longText('description')->nullable();
            $table->enum('points', [1, 3, 5, 8])->nullable();
            $table->integer('iteration')->nullable();
            $table->enum('type', ['feature', 'bug']);
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
        Schema::dropIfExists('cards');
    }
}