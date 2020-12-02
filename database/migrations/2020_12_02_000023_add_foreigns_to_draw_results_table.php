<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToDrawResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('draw_results', function (Blueprint $table) {
            $table
                ->foreign('bet_game_id')
                ->references('id')
                ->on('bet_games');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('draw_results', function (Blueprint $table) {
            $table->dropForeign(['bet_game_id']);
        });
    }
}
