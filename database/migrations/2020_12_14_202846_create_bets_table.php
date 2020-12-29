<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBetsTable extends Migration
{

    public function up()
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->string('combination');
            $table->double('amount');
            $table->boolean('is_rumbled')->default(false);
            $table->boolean('is_voided')->default(false);
            $table->unsignedBigInteger('bet_transaction_id');
            $table->unsignedBigInteger('draw_period_id');
            $table->unsignedBigInteger('game_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bets');
    }
}
