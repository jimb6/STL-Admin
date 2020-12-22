<?php

namespace App\Console\Commands;

use App\Models\CloseNumber;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CloseNumberAutoTruncate extends Command
{

    protected $signature = 'close_number:truncate';

    protected $description = 'Truncate Close Numbers Day After Created';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        CloseNumber::where('created_at', '<', Carbon::now())->each(function ($item) {
            $item->delete();
        });
    }
}
