<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationUpdateRequest extends FormRequest
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
            'type_id' => 'integer',
            'note' => 'string|min:10',
            'requested_date' => 'date_format:Y-m-d H:i:s|before_or_equal:time_off_beginning',
        ];
    }
}
