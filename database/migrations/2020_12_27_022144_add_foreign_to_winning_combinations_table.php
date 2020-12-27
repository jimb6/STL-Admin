<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignToWinningCombinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('winning_combinations', function (Blueprint $table) {
            $table->foreign('draw_period_id')
                ->references('id')
                ->on('draw_periods');

            $table->foreign('game_id')
                ->references('id')
                ->on('games');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('winning_combinations', function (Blueprint $table) {
            $table->dropForeign('draw_period_id');
            $table->dropForeign('game_id');
        });
    }
}
