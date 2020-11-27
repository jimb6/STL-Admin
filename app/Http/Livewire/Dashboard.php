<?php

namespace App\Http\Livewire;

use App\Models\Transaction;
use Livewire\Component;

class Dashboard extends Component
{

    public $count = 0;

    public function increment()
    {
        $this->count++;
    }

    public function render()
    {

        $transactions = Transaction::with('bets')->get();
        return view('livewire.dashboard', [
            'transactions' => $transactions
        ]);
    }
}
