<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeAbsenceUpdateRequest extends FormRequest
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
            'type' => 'string|max:191',
            'time_details' => 'date_format:Y-m-d',
            'at_time' => 'string|max:191',
        ];
    }
}
