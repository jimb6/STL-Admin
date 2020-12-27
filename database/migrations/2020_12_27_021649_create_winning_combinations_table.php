<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWinningCombinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winning_combinations', function (Blueprint $table) {
            $table->id();
            $table->string('combination');
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('draw_period_id');
            $table->timestamp('verified_at')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('winning_combinations');
    }
}
