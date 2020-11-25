<?php

namespace App\Rules;

use App\Models\AppSettings;
use App\Models\DrawPeriod;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class TransactionRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //Get Time Category Information from database
        $drawPeriod = DrawPeriod::find(1)->where(['name' => $value->name]);
        //Get Time Closing in App Settings
        $appSettings = new AppSettings();
//        $appSettings->findOrFail(1)->where(['key' => $value->]);

        $carbon = Carbon::now();
        $date = $carbon->format('d');
        $year = $carbon->get('year');
        $month = $carbon->get('month');
        $hour = $carbon->get('hour');
        $mins = $carbon->format('i');
        $second = $carbon->get('second');

        $startTime = Carbon::parse();
        $twoPmClosedTime = Carbon::parse();


        if ($value === "2PM"){

        }
        $todayDateTime = Carbon::now()->greaterThanOrEqualTo();
//        return
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute Invalid draw period.';
    }
}
