<?php

namespace App\Http\Requests;

use App\Models\Trust\Role;

class UserCreateRequest extends BaseRequest
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
            'name' => 'sometimes|required|string|max:191',
            'email' => 'sometimes|required|string|unique:users,email',
            'phone' => 'sometimes|string|unique:users,phone',
            'photo' => 'sometimes|nullable|numeric|exists:images',
            'password' => 'sometimes|required|string',
            'address' => 'sometimes|nullable|string|max:191',
            'role' => 'sometimes|required|string|in:' . implode(',', Role::roles()),
            'first_workday' => 'sometimes|required|date_format:Y-m-d',
            'team_id' => 'sometimes|required',
            'position_id' => 'sometimes|required',
        ];
    }

    /**
     * @return array Custom message errors
     */
    public function messages()
    {
        return [
            'role.in' => 'The selected role is invalid. Must be in ' . implode(', ', Role::roles()),
        ];
    }
}
