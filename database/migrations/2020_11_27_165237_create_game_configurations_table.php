<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_configurations', function (Blueprint $table) {
            $table->id();
            $table->integer('number_set');
            $table->integer('limit_per_set');
            $table->boolean('has_repetition');
            $table->string('number_format');
            $table->string('days_availability');
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
        Schema::dropIfExists('game_configurations');
    }
}
