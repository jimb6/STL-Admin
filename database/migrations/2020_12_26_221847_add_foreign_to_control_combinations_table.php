<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignToControlCombinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('control_combinations', function (Blueprint $table) {
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
        Schema::table('control_combinations', function (Blueprint $table) {
            $table->dropForeign('game_id');
        });
    }
}
