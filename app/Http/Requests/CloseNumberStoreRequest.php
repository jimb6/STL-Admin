<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CloseNumberStoreRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'bet_game_id' => ['required', 'exists:bet_games,id'],
            'number_value' => ['required', 'max:255', 'string'],
        ];
    }
}
