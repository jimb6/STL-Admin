<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_records', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id');
            $table->bigIncrements('id');
            $table->double('collectionAmount');
            $table->dateTime('collectionDate');
            $table->string('remarks');
            $table->unsignedBigInteger('collection_status_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_records');
    }
}
