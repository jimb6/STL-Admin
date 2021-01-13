<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignToWinningBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('winning_bets', function (Blueprint $table) {
            $table->foreign('winning_combination_id')
                ->references('id')
                ->on('winning_combinations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('winning_bets', function (Blueprint $table) {
            $table->dropForeign('winning_combination_id');
        });
    }
}
