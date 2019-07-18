<?php
namespace App\Services;

use App\Models\Registration;
use App\Models\TimeAbsence;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimeAbsenceService
{
    public static function add($id, array $attribute)
    {
        $userCurrent = Auth::id();
        $checkUser = Registration::where('user_id', $userCurrent)->get();
        // dd($checkUser[0]);
        for ($i=0; $i < count($checkUser) ; $i++) {
            $findRegistration[] = TimeAbsence::where('registration_id', $checkUser[$i]->id)->get();
            if($findRegistration[$i] == null) {
                unset($findRegistration[$i]);
            }
        }
        // dd(count($findRegistration[1]));
        dd($findRegistration);
        for ($i=0; $i < count($findRegistration); $i++) { 
            $findTimeDetail[] = $findRegistration[0][$i]->time_details;
        }
        dd($findTimeDetail);

        if ($attribute['type'] == Registration::TYPE_ABSENCE) {
            $timeStart = new Carbon($attribute['time_start']);
            $timeEnd = new Carbon($attribute['time_end']);
            $checkTimeDuplicate = TimeAbsence::where('time_details', $timeStart)->get();

            if($checkTimeDuplicate != null) {
                for ($i=0; $i < count($checkTimeDuplicate); $i++) { 
                    $userId[] = Registration::where('id', $checkTimeDuplicate[$i]->registration_id)->get();
                }

                for ($i=0; $i < count($userId) ; $i++) { 
                    if($userId[$i][0]->user_id == $userCurrent) {
                        return false;
                    }
                }
            }
            $n = $timeEnd->diffInDays($timeStart) + 1;
            for ($i = 0; $i < $n; $i++) {
                $timeAbsence = new TimeAbsence;
                $timeAbsence->registration_id = $id;
                $timeAbsence->type = $attribute['type'];
                $timeAbsence->time_details = $timeStart->toDateString();
                $timeAbsence->at_time = Registration::FULL;
                $timeAbsence->absence_days = 1;
                $timeAbsence->current_year = Carbon::parse($timeStart->toDateString())->format('Y');
                $timeAbsence->general_information = 'The time absence of you at: ' .$timeStart;
                $timeStart = $timeStart->addDay();
                $timeAbsence->save();
            }
        } else {
            $time = explode(';', $attribute['date']);
            for ($i = 0; $i < count($time); $i++) {
                $timeChildren = explode(',', $time[$i]);
                dd($timeChildren);
                $newTimeAbsence = new TimeAbsence;
                $newTimeAbsence->registration_id = $id;
                $newTimeAbsence->type = $attribute['type'];
                $newTimeAbsence->time_details = $timeChildren[0];
                $newTimeAbsence->at_time = $timeChildren[1];
                if ($newTimeAbsence->at_time == Registration::MORNING || $newTimeAbsence->at_time == Registration::AFTERNOON) {
                    $newTimeAbsence->absence_days = 0.5;
                } else {
                    $newTimeAbsence->absence_days = 1;
                }
                $newTimeAbsence->current_year = Carbon::parse($timeChildren[0])->format('Y');
                $newTimeAbsence->general_information = 'Time absence of you at: ' . $timeChildren[1] . $timeChildren[0];
                $newTimeAbsence->save();
            }
        }
    }

    public static function update($id, array $attribute)
    {
        $oldTimeDelete = TimeAbsence::where('registration_id', $id)->delete();
        if ($attribute['type'] == Registration::TYPE_ABSENCE) {
            $timeStart = new Carbon($attribute['time_start']);
            $timeEnd = new Carbon($attribute['time_end']);
            $n = $timeEnd->diffInDays($timeStart) + 1;
            for ($i = 0; $i < $n; $i++) {
                $timeAbsence = new TimeAbsence;
                $timeAbsence->registration_id = $id;
                $timeAbsence->type = $attribute['type'];
                $timeAbsence->time_details = $timeStart->toDateString();
                $timeAbsence->at_time = Registration::FULL;
                $timeAbsence->absence_days = 1;
                $timeAbsence->current_year = Carbon::parse($timeStart->toDateString())->format('Y');
                $timeAbsence->general_information = 'The time absence of you at: ' . $timeStart;
                $timeStart = $timeStart->addDay();
                $timeAbsence->save();
            }
        } else {
            $time = explode(';', $attribute['date']);
            for ($i = 0; $i < count($time); $i++) {
                $timeChildren = explode(',', $time[$i]);
                $newTimeAbsence = new TimeAbsence;
                $newTimeAbsence->registration_id = $id;
                $newTimeAbsence->type = $attribute['type'];
                $newTimeAbsence->time_details = $timeChildren[0];
                $newTimeAbsence->at_time = $timeChildren[1];
                if ($newTimeAbsence->at_time == Registration::MORNING || $newTimeAbsence->at_time == Registration::AFTERNOON) {
                    $newTimeAbsence->absence_days = 0.5;
                } else {
                    $newTimeAbsence->absence_days = 1;
                }
                $newTimeAbsence->current_year = Carbon::parse($timeChildren[0])->format('Y');
                $newTimeAbsence->general_information = 'Time absence of you at: ' . $timeChildren[1] . $timeChildren[0];
                $newTimeAbsence->save();
            }
        }
    }

    public static function search($id, $attributes)
    {
        $arrayId = array();
        foreach ($id as $value) {
            $arrayId[] = $value->id;
        }
        if (isset($attributes['day'])) {
            $time = $attributes['day'];
            $registrationId = TimeAbsence::whereIn('registration_id', $arrayId)->where('time_details', $time)->select('registration_id')->get();
            return $registrationId;
        } elseif (isset($attributes['month'])) {
            $time = $attributes['month'];
            $cutTime = explode('-', $time);
            $month = $cutTime[1];
            $year = $cutTime[0];
            $registrationId = TimeAbsence::whereIn('registration_id', $arrayId)->whereMonth('time_details', $month)->whereYear('time_details', $year)->select('registration_id')->get();
            return $registrationId;
        } else {
            $time = $attributes['year'];
            $registrationId = TimeAbsence::whereIn('registration_id', $arrayId)->whereYear('time_details', $time)->select('registration_id')->get();
            return $registrationId;
        }
    }
}
