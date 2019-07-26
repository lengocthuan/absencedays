<?php
namespace App\Services;

use App\Models\Registration;
use App\Models\TimeAbsence;
use App\Models\Track;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimeAbsenceService
{
    public static function add($id, array $attribute)
    {
        $userCurrent = Auth::id();
        if ($attribute['type'] == Registration::TYPE_ABSENCE) {
            $timeStart = new Carbon($attribute['time_start']);
            $timeEnd = new Carbon($attribute['time_end']);

            $countDay = $timeEnd->diffInDays($timeStart) + 1;
            for ($i = 0; $i < $countDay; $i++) {
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

    public static function check($attribute)
    {
        $userCurrent = Auth::id();
        if ($attribute['type'] == Registration::TYPE_ABSENCE) {
            $timeStart = new Carbon($attribute['time_start']);
            $timeEnd = new Carbon($attribute['time_end']);
            $countDay = $timeEnd->diffInDays($timeStart) + 1;

            for ($i = 0; $i < $countDay; $i++) {
                $checkTimeDuplicate = TimeAbsence::where('time_details', $timeStart)->get();
                if (count($checkTimeDuplicate)) {
                    $userId = [];
                    for ($i = 0; $i < count($checkTimeDuplicate); $i++) {
                        if (isset($attribute['_method'])) {
                            $temp = Registration::where('id', $checkTimeDuplicate[$i]->registration_id)->whereNotIn('id', [$attribute['id']])->get();
                            if (!$temp->isEmpty()) {
                                $userId[] = $temp;
                            }
                        } else {
                            $userId[] = Registration::where('id', $checkTimeDuplicate[$i]->registration_id)->get();
                        }
                    }
                    if (count($userId)) {
                        for ($i = 0; $i < count($userId); $i++) {
                            if ($userId[$i][0]->user_id == $userCurrent) {
                                return false;
                            }
                        }
                    }

                }
                $timeStart->addDay();
            }

            return true;
        } else {
            $time = explode(';', $attribute['date']);
            for ($i = 0; $i < count($time); $i++) {
                $timeChildren = explode(',', $time[$i]);

                if ($timeChildren[1] == Registration::FULL) {
                    $checkTimeDuplicate = TimeAbsence::where('time_details', $timeChildren[0])->get();
                    if (count($checkTimeDuplicate)) {
                        $userId = [];
                        for ($i = 0; $i < count($checkTimeDuplicate); $i++) {
                            if (isset($attribute['_method'])) {
                                $temp = Registration::where('id', $checkTimeDuplicate[$i]->registration_id)->whereNotIn('id', [$attribute['id']])->get();
                                if (!$temp->isEmpty()) {
                                    $userId[] = $temp;
                                }
                            } else {
                                $userId[] = Registration::where('id', $checkTimeDuplicate[$i]->registration_id)->get();
                            }
                        }
                        if (count($userId)) {
                            for ($i = 0; $i < count($userId); $i++) {
                                if ($userId[$i][0]->user_id == $userCurrent) {
                                    return false;
                                }
                            }
                        }
                    }
                } else {
                    $checkTimeDuplicate = TimeAbsence::where(['time_details' => $timeChildren[0], 'at_time' => $timeChildren[1]])->get();
                    if (count($checkTimeDuplicate)) {
                        $userId = [];
                        for ($i = 0; $i < count($checkTimeDuplicate); $i++) {
                            if (isset($attribute['_method'])) {
                                $temp = Registration::where('id', $checkTimeDuplicate[$i]->registration_id)->whereNotIn('id', [$attribute['id']])->get();
                                if (!$temp->isEmpty()) {
                                    $userId[] = $temp;
                                }
                            } else {
                                $userId[] = Registration::where('id', $checkTimeDuplicate[$i]->registration_id)->get();
                            }
                        }
                        if (count($userId)) {
                            for ($i = 0; $i < count($userId); $i++) {
                                if ($userId[$i][0]->user_id == $userCurrent) {
                                    return false;
                                }
                            }
                        }

                    }
                }
            }
            return true;
        }
    }

    public static function checkTrack($attribute)
    {
        $day = 0;
        $userCurrent = Auth::id();
        if ($attribute['type'] == Registration::TYPE_ABSENCE) {
            $timeStart = new Carbon($attribute['time_start']);
            $timeEnd = new Carbon($attribute['time_end']);

            $countDay = $timeEnd->diffInDays($timeStart) + 1;
            for ($i = 0; $i < $countDay; $i++) {
                // $day += 1;
                $current_year[] = Carbon::parse($timeStart->toDateString())->format('Y');
                $total = Track::where('user_id', $userCurrent)->where('year', $current_year)->select(['annual_leave_unused'])->first();
                $tru = (double)$total->annual_leave_unused - 1;
                $timeStart = $timeStart->addDay();
            }
            // dd($current_year);

            dd($current_year);
        } else {
            $time = explode(';', $attribute['date']);
            for ($i = 0; $i < count($time); $i++) {
                $timeChildren = explode(',', $time[$i]);
                if ($timeChildren[1] == Registration::MORNING || $timeChildren[1] == Registration::AFTERNOON) {
                    $day += 0.5;
                } else {
                    $day += 1;
                }
            }
            dd($day);
        }
    }

    public static function delete($id)
    {
        $oldTimeDelete = TimeAbsence::where('registration_id', $id)->delete();
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
