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
            $table->unsignedBigInteger('game_id');
            $table->integer('field_set');
            $table->integer('digit_per_field_set');
            $table->boolean('has_repetition');
            $table->boolean('is_rumbled')->default(false);
            $table->double('max_sum_bet');
            $table->double('main_per_bet')->default(0);
            $table->double('multiplier');
            $table->string('transaction_limit');
            $table->double('max_per_bet');
            $table->json('days_availability');
            $table->softDeletes();
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
