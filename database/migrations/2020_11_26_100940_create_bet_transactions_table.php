<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBetTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bet_transactions', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->integer('transaction_id')->unsigned()->index();
            $table->integer('game_category_id')->unsigned()->index();
            $table->integer('draw_period_id')->unsigned()->index();
            $table->string('combination');
            $table->double('amount');
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
        Schema::dropIfExists('bet_transactions');
    }
}
