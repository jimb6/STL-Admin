<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToCollectionRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collection_records', function (Blueprint $table) {
            $table
                ->foreign('agent_id')
                ->references('id')
                ->on('agents');
            $table
                ->foreign('collection_status_id')
                ->references('id')
                ->on('collection_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collection_records', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropForeign(['collection_status_id']);
        });
    }
}
