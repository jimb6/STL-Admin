<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrawResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draw_results', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->date('date_result');
            $table->string('combination');
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
        Schema::dropIfExists('draw_results');
    }
}
