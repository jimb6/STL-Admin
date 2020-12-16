<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignToBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bets', function (Blueprint $table) {
            $table->foreign('bet_transaction_id')
                ->references('id')
                ->on('bet_transactions');

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
        Schema::table('bets', function (Blueprint $table) {
            $table->dropForeign('bet_transaction_id');
            $table->dropForeign('draw_period_id');
            $table->dropForeign('game_id');
        });
    }
}
