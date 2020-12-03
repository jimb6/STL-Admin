<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectionRecordStoreRequest extends FormRequest
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
            'agent_id' => ['required', 'exists:agents,id'],
            'collection_status_id' => [
                'required',
                'exists:collection_statuses,id',
            ],
            'collectionAmount' => ['required', 'numeric'],
            'collectionDate' => ['required', 'date', 'date'],
            'remarks' => ['required', 'max:255', 'string'],
        ];
    }
}
