<?php
namespace App\Services;

use App\Models\Registration;
use App\Models\TimeAbsence;
use Carbon\Carbon;

class TimeAbsenceService
{
    // public static function totalday($registration_id, $sum)
    // {
    //     $registration = Registration::find($registration_id);
    //     $registration->absence_days = $sum;
    //     $registration->save();
    // }
    public static function add($id, array $attribute)
    {

        if ($attribute['type'] == 'From day to day') {

            $timestart = new Carbon($attribute['time_start']);
            $timeend = new Carbon($attribute['time_end']);
            // $count = $timestart->toDateString();
            $n = $timeend->diffInDays($timestart) + 1;
            for ($i = 0; $i < $n; $i++) {
                $ta = new TimeAbsence;
                $ta->registration_id = $id;
                $ta->type = $attribute['type'];
                $ta->time_details = $timestart->toDateString();
                $timestart = $timestart->addDay();
                $ta->at_time = 'Full';
                $ta->absence_days = 1;
                $ta->current_year = Carbon::parse($timestart->toDateString())->format('Y');
                $ta->general_information = 'The time absence of you at: ' . $timestart->toDateString();
                $ta->save();
                // return $ta;
            }
        } else {
            $time = explode(';', $attribute['date']);
            for ($i = 0; $i < count($time); $i++) {
                $time_children = explode(',', $time[$i]);

                $details = new TimeAbsence;
                $details->registration_id = $id;
                $details->type = $attribute['type'];
                $details->time_details = $time_children[0];
                $details->at_time = $time_children[1];
                if($details->at_time == 'Morning' || $details->at_time == 'Afternoon') {
                    $details->absence_days = 0.5;
                } else $details->absence_days = 1;
                // $time = new Carbon($details->time_details);
                $details->current_year = Carbon::parse($time_children[0])->format('Y');
                $details->general_information = 'Time absence of you at: ' . $time_children[1] . $time_children[0];
                $details->save();
                // return $details;
            }

            // $registration = new Registration;
            // $registration->current_year = $timeend->year;
            // $registration->general_information = "Time absence of you start at : " . $timestart->toDateString() . "and end at: " . $timeend->toDateString();
            // $registration->absence_days = $timeend->diffInDays($timestart);
            // $registration->save();
        }

    }

    // public function addTime1($id, array $attribute)
    // {
    //     $time = explode(';', $attribute['date']);
    //     for ($i = 0; $i < count($time); $i++) {
    //         $time_children = explode(',', $time[$i]);

    //         $details = new TimeAbsence;
    //         $details->registration_id = $id;
    //         $details->type = $attribute['type'];
    //         $details->time_details = $time_children[0];
    //         $details->at_time = $time_children[1];
    //         $details->save();
    //     }
    // }

    public static function checkTime(array $id, array $attribute)
    {
        $arr = array();
        for ($i = 0; $i < count($id); $i++) {
            $check = Registration::where(['id' => $id[$i]]);
            $arr[] = $check;
        }
        dd($arr);
    }
    // public static function sync(User $user, $roleName)
    // {
    //     if (!$roleName) {
    //         throwError('Please insert role name', 422);
    //     }
    //     if (!empty($roleName) && !in_array($roleName, Role::roles())) {
    //         throwError('Some thing went wrong!', 500);
    //     }
    //     // find or create role admin
    //     $role = Role::firstOrCreate(['name' => $roleName]);

    //     return $user->roles()->sync($role);
    // }
}
