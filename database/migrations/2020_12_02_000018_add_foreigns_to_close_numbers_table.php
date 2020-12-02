<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToCloseNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('close_numbers', function (Blueprint $table) {
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
        Schema::table('close_numbers', function (Blueprint $table) {
            $table->dropForeign(['bet_game_id']);
        });
    }
}
