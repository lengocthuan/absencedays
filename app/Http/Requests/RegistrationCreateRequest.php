<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationCreateRequest extends FormRequest
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
            'user_id' => 'required|string|max:191',
            'type_id' => 'required|string|max:191',
            'note' => 'required|string|min:20',
            'status' => 'required|integer|max:3|min:0',
            'requested_date' => 'date_format:Y-m-d H:i:s|before_or_equal:time_off_beginning',
            'approved_date' => 'date_format:Y-m-d H:i:s|after_or_equal:time_off_beginning',
            'time_off_beginning' => 'required|date_format:Y-m-d|before_or_equal:time_off_ending',
            'time_off_ending' => 'required|date_format:Y-m-d|after_or_equal:time_off_beginning',
            'current_year' => 'integer|min:1900|max:2500',
            'annual_leave_total' => 'numeric|between:0.5,99.99',
            'annual_leave' => 'numeric|between:0.5,99.99',
            'annual_leave_unused' => 'numeric|between:0.5,99.99',
            'sick_leave' => 'numeric|between:0.5,99.99',
            'marriage_leave' => 'numeric|between:0.5,99.99',
            'maternity_leave' => 'numeric|between:0.5,99.99',
            'bereavement_leave' => 'numeric|between:0.5,99.99',
            'long_term_unpaid_leave' => 'numeric|between:0.5,99.99',
            'short_term_unpaid_leave' => 'numeric|between:0.5,99.99',
            'at_time' => 'required',
        ];
    }
}
