<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToBetTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bet_transactions', function (Blueprint $table) {
            $table
                ->foreign('agent_id')
                ->references('id')
                ->on('agents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bet_transactions', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
        });
    }
}
