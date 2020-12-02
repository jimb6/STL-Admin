<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToWinnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('winners', function (Blueprint $table) {
            $table
                ->foreign('bet_id')
                ->references('id')
                ->on('bets');
            $table
                ->foreign('draw_result_id')
                ->references('id')
                ->on('draw_results');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('winners', function (Blueprint $table) {
            $table->dropForeign(['bet_id']);
            $table->dropForeign(['draw_result_id']);
        });
    }
}
