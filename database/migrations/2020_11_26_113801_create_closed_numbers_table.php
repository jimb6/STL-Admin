<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClosedNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closed_numbers', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->string('number_value')->unique();
            $table->string('closed_by');
            $table->integer('draw_period_id')->unsigned()->index();
            $table->integer('game_category_id')->unsigned()->index();
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
        Schema::dropIfExists('closed_numbers');
    }
}
