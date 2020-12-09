<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BoothUpdateRequest extends FormRequest
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
            'base_id' => ['required', 'exists:bases,id'],
            'location' => ['required', 'max:255', 'string'],
            'status' => ['required', 'max:255', 'string'],
        ];
    }
}
