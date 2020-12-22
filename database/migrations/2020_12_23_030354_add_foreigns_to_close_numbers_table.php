<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->foreign('game_id')
                ->references('id')
                ->on('games');
            $table->foreign('draw_period_id')
                ->references('id')
                ->on('draw_periods');
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
            $table->dropForeign(['draw_period_id', 'game_id']);
        });
    }
}
