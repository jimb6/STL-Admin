<?php

use Illuminate\Database\Migrations\Migration;

class CreateSumDailyCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP procedure IF EXISTS sumDailyCollection");
        DB::unprepared('
CREATE PROCEDURE sumDailyCollection (
    IN base_id INTEGER
)
BEGIN
    SELECT sum(b.amount) as `sum_amount`
    FROM bets_today b
    WHERE b.bet_transaction_id in (SELECT bt.id
                                   FROM bet_transactions bt
                                   WHERE bt.agent_id in (SELECT a.id
                                                         FROM agents a,
                                                              bases b
                                                         WHERE a.base_id = base_id
                                                         GROUP BY a.id));
END ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
