<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackCreateRequest extends FormRequest
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
            'year' => 'sometimes|required',
            'from' => 'date_format:Y-m-d|before_or_equal:to',
            'to' => 'date_format:Y-m-d|after_or_equal:from',
            'absences' => 'sometimes|numeric|max:1',
        ];
    }
}
