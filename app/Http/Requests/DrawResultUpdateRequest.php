<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DrawResultUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bet_game_id' => ['required', 'exists:bet_games,id'],
        ];
    }
}
