<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeAbsenceCreateRequest extends FormRequest
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
            'registration_id' => 'string|max:191',
            'type' => 'string|max:191',
            'time_details' => 'date_format:Y-m-d',
            'time_start' => 'date_format:Y-m-d|before_or_equal:time_end',
            'time_end' => 'date_format:Y-m-d|after_or_equal:time_start',
            'at_time' => 'string|max:191',
        ];
    }
}
