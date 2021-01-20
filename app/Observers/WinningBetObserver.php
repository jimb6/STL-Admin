<?php

namespace App\Observers;

use App\Models\WinningBet;
use App\Models\WinningCombination;
use Illuminate\Support\Facades\DB;

class WinningBetObserver
{

    public function created(WinningBet $winningBet)
    {
        //
    }

    public function updated(WinningBet $winningBet)
    {
        $winningCombination = WinningCombination::where('id', $winningBet->winning_combination_id)->first();
        $deleteBets = DB::table('winning_bets')->where('winning_combination_id', $winningCombination->id)->delete();
        $insertBets = DB::table('winning_bets')
            ->insertUsing(
                ['bet_id', 'winning_combination_id', 'created_at', 'updated_at'],
                "SELECT
                        b.id as bet_id,
                        {$winningCombination->id} as winning_combination_id,
                        '{$winningCombination->created_at}' as created_at,
                        '{$winningCombination->created_at}' as updated_at
                        FROM bets b
                        WHERE b.game_id = {$winningCombination->game_id}
                        AND b.combination = {$winningCombination->combination}
                        AND b.draw_period_id = {$winningCombination->draw_period_id}
                        AND DATE(b.created_at) = DATE('{$winningCombination->created_at}')"
            );
    }

    public function deleted(WinningBet $winningBet)
    {
        //
    }

    public function restored(WinningBet $winningBet)
    {
        //
    }

    public function forceDeleted(WinningBet $winningBet)
    {
        //
    }
}
