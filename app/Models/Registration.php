<?php

namespace App\Models;

use App\User;
use App\Models\Type;
/**
 * Class Registration.
 *
 * @package namespace App\Models;
 */
class Registration extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'type_id', 'note', 'status', 'requested_date', 'approved_date', 'time_off_beginning', 'time_off_ending', 'current_year', 'annual_leave_total', 'absence_days', 'annual_leave_unused', 'at_time', 'general_information'];

    public function getUser() {
        // $user = User::find($this->user_id);
        // return $user->name;
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function getType() {
        // $type = Type::find($this->type_id);
        // return $type->name;
        return $this->belongsTo(\App\Models\Type::class, 'type_id');
    }
    // protected function validator(array $data)
    // {
    //     // return Validator::make([
    //     //     'user_id' => 'required|string|max:191',
    //     //     'type_id' => 'required|string|max:191',
    //     //     'note' => 'required|string|min:49',
    //     //     'user_id' => 'required|string|max:191',
    //     //     'user_id' => 'required|string|max:191',
    //     //     'user_id' => 'required|string|max:191',
    //     // ]);
    // }

    // protected function create(array $data)
    // {
    //     // return Registration::create([
    //     //     // 'user_id' => $data['user_id'],
    //     //     // 'type_id' => $data['type_id'],
    //     //     // 'note' => $data['note'],
    //     //     // 'status' => $data['status'],
    //     //     // 'requested_date' => $data['requested_date'],
    //     //     // 'approved_date' => $data['approved_date'],
    //     //     // 'time_off_beginning' => $data['time_off_beginning'],
    //     //     // 'time_off_ending' => $data['time_off_ending'],
    //     //     // 'current_year' => $data['current_year'],
    //     //     // 'annual_leave_total' => $data['annual_leave_total'],
    //     //     // 'annual_leave' => $data['annual_leave'],
    //     //     // 'annual_leave_unused' => $data['annual_leave_unused'],
    //     //     // 'sick_leave' => $data['sick_leave'],
    //     //     // 'marriage_leave' => $data['marriage_leave'],
    //     //     // 'maternity_leave' => $data['maternity_leave'],
    //     //     // 'bereavement_leave' => $data['bereavement_leave'],
    //     //     // 'long_term_unpaid_leave' => $data['long_term_unpaid_leave'],
    //     //     // 'short_term_unpaid_leave' => $data['short_term_unpaid_leave'],
    //     // ]);
    // }
}
