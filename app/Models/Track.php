<?php

namespace App\Models;

use App\Models\Registration;
use App\Models\TimeAbsence;
use App\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class Track.
 *
 * @package namespace App\Models;
 */
class Track extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'year', 'annual_leave_total', 'annual_leave_unused', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    public function getUser()
    {
        $track = $this->belongsTo(\App\User::class, 'user_id');
        return $track;
    }

    public function getRegistration()
    {
        $id = Auth::user()->id;
        // dd($id);
        $registration = Registration::where('user_id', $id)->get();
        $arr = array();
        for ($i = 0; $i < count($registration); $i++) {
            $arr[] = $registration[$i]->id;
        }
        // dd($arr);
        $value = array();
        $arr2 = array();
        for ($i = 0; $i < count($arr); $i++) {
            $time = TimeAbsence::where('registration_id', $arr[$i])->get();
            // dd($time);
            $value[] = $time;
        }
        foreach ($value as $val) {
            for ($i = 0; $i < count($val); $i++) {
                $merge = ['id' => $val[$i]['id'], 'registration_id' => $val[$i]['registration_id'], 'type' => $val[$i]['type'], 'time_details' => $val[$i]['time_details'], 'at_time' => $val[$i]['at_time'], 'absence_days' => $val[$i]['absence_days'], 'current_year' => $val[$i]['current_year'], 'general_information' => $val[$i]['general_information'], 'created_at' => $val[$i]['created_at'], 'updated_at' => $val[$i]['updated_at']];
                $arr2[] = $merge;
            }
        }
        return $arr2;
    }
}
