<?php

namespace App\Models;

use App\User;
use App\Models\Type;
use App\Models\TimeAbsence;
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

    public function getTimeAbsence() {
        return $this->hasMany(\App\Models\TimeAbsence::class, 'registration_id');
    }
    
}
