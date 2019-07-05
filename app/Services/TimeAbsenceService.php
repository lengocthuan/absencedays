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
        if ($attribute['type'] == 'Từ ngày đến ngày') {

            $timestart = new Carbon($attribute['time_start']);
            $timeend = new Carbon($attribute['time_end']);
            $n = $timeend->diffInDays($timestart) + 1;
            for ($i = 0; $i < $n; $i++) {
                $ta = new TimeAbsence;
                $ta->registration_id = $id;
                $ta->type = $attribute['type'];
                $ta->time_details = $timestart->toDateString();
                $ta->at_time = 'Cả Ngày';
                $ta->absence_days = 1;
                $ta->current_year = Carbon::parse($timestart->toDateString())->format('Y');
                $ta->general_information = 'The time absence of you at: ' .$timestart;
                $timestart = $timestart->addDay();
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
                if ($details->at_time == 'Buổi Sáng' || $details->at_time == 'Buổi Chiều') {
                    $details->absence_days = 0.5;
                } else {
                    $details->absence_days = 1;
                }

                // $time = new Carbon($details->time_details);
                $details->current_year = Carbon::parse($time_children[0])->format('Y');
                $details->general_information = 'Time absence of you at: ' . $time_children[1] . $time_children[0];
                $details->save();
                // return $details;
            }
        }

    }


    public static function update($id, array $attribute)
    {
        $timedt = TimeAbsence::where('registration_id', $id)->delete();
        if ($attribute['type'] == 'Từ ngày đến ngày') {

            $timestart = new Carbon($attribute['time_start']);
            $timeend = new Carbon($attribute['time_end']);
            $n = $timeend->diffInDays($timestart) + 1;
            for ($i = 0; $i < $n; $i++) {
                $ta = new TimeAbsence;
                // $ta = TimeAbsence::find($id);
                $ta->registration_id = $id;
                $ta->type = $attribute['type'];
                $ta->time_details = $timestart->toDateString();
                $ta->at_time = 'Cả Ngày';
                $ta->absence_days = 1;
                $ta->current_year = Carbon::parse($timestart->toDateString())->format('Y');
                $ta->general_information = 'The time absence of you at: ' .$timestart;
                $timestart = $timestart->addDay();
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
                if ($details->at_time == 'Buổi Sáng' || $details->at_time == 'Buổi Chiều') {
                    $details->absence_days = 0.5;
                } else {
                    $details->absence_days = 1;
                }

                // $time = new Carbon($details->time_details);
                $details->current_year = Carbon::parse($time_children[0])->format('Y');
                $details->general_information = 'Time absence of you at: ' . $time_children[1] . $time_children[0];
                $details->save();
                // return $details;
            }
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
    public static function search($id, $attributes)
    {
        $arr = array();
        foreach ($id as $value) {
            $arr[] = $value->id;
        }
        if (isset($attributes['day'])) {
            $time = $attributes['day'];
            $registration_id = TimeAbsence::whereIn('registration_id', $arr)->where('time_details', $time)->select('registration_id')->get();
            return $registration_id;
        } elseif (isset($attributes['month'])) {
            $time = $attributes['month'];
            $a = explode('-', $time);
            $month = $a[1];
            $year = $a[0];
            $registration_id = TimeAbsence::whereIn('registration_id', $arr)->whereMonth('time_details', $month)->whereYear('time_details', $year)->select('registration_id')->get();
            return $registration_id;
        } else {
            $time = $attributes['year'];
            $registration_id = TimeAbsence::whereIn('registration_id', $arr)->whereYear('time_details', $time)->select('registration_id')->get();
            return $registration_id;
        }
// >where(function ($query) use ($daystart, $dayend)

    }
}
