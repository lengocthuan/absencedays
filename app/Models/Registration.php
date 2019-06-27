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
    protected $fillable = ['id', 'user_id', 'type_id', 'note', 'status', 'requested_date', 'aprroved_date', 'time_off_beginning', 'time_off_ending', 'current_year', 'annual_leave_total', 'absence_days', 'annual_leave_unused', 'at_time', 'general_information', 'approver_id'];

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
        $timeAB =  $this->hasMany(\App\Models\TimeAbsence::class, 'registration_id');
        return $timeAB;
    }
    
    public function getTotalTime() {
        // $this->hasMany(\App\Models\TimeAbsence::class, 'registration_id');
        // $total = $this->getTimeAbsence;
        // dd($total);
        // $user = User::find($this->user_id);
        // return $user->name;
        $total = TimeAbsence::where('registration_id', $this->id)->select('absence_days')->get();
        // dd($total);
        $sum = 0;
        foreach ($total as $value) {
            $sum += $value->absence_days;
        }
        return $sum;
    }

    public function getApprover() {
        return $this->belongsTo(\App\User::class, 'approver_id');
    }
}
