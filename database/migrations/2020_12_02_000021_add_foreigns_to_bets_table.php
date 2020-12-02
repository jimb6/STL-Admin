<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bets', function (Blueprint $table) {
            $table
                ->foreign('bet_game_id')
                ->references('id')
                ->on('bet_games');
            $table
                ->foreign('bet_transaction_id')
                ->references('id')
                ->on('bet_transactions');
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
            $table->dropForeign(['bet_game_id']);
            $table->dropForeign(['bet_transaction_id']);
        });
    }
}
