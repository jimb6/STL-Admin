<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{

    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serial_number')->unique()->nullable();
            $table->unsignedBigInteger('cluster_id');
            $table->unsignedBigInteger('user_id')->unique()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
