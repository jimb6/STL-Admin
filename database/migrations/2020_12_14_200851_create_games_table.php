<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('abbreviation');
            $table->double('prize');
            $table->double('max_bet')->default(1000);
            $table->integer('field_set');
            $table->integer('digit_per_field_set');
            $table->integer('min_number');
            $table->integer('max_number');
            $table->boolean('has_repetition');
            $table->boolean('is_rumbled')->default(false);
            $table->json('days_availability');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
