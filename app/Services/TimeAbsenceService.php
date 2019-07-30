<?php
namespace App\Services;

use App\Models\Registration;
use App\Models\TimeAbsence;
use App\Models\Track;
use App\Services\TrackService;
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

    public static function process($time, $now, $userCurrent, $at_time)
    {
        $result = 0;
        $newResult = 0;
        $temp = Carbon::parse($time)->format('Y');
        $checkTotal = Track::where('user_id', $userCurrent)->where('year', $temp)->select(['annual_leave_unused'])->first();
        if (is_null($checkTotal)) {
            $add = new Track;
            $add->year = $temp;
            $add->user_id = $userCurrent;
            $add->annual_leave_total = TrackService::calculatorYear($userCurrent, $temp);
            $add->annual_leave_unused = $add->annual_leave_total;
            if ($add->annual_leave_total == false) {
                return false;
            }
            $add->save();
        }

        if ($temp == $now) {
            if ($at_time == Registration::MORNING || $at_time == Registration::AFTERNOON) {
                $result += 0.5;
            } else {
                $result += 1;
            }
        } else {
            if ($at_time == Registration::MORNING || $at_time == Registration::AFTERNOON) {
                $newResult += 0.5;
            } else {
                $newResult += 1;
            }
        }
        return $result . '-' . $newResult;

    }
    public static function checkTrack($attribute)
    {
        $day = 0;
        $now = Carbon::now()->format('Y');
        $result = 0;
        $newResult = 0;
        $userCurrent = Auth::id();
        // if ($attribute['type_id'] != Registration::ANNUAL_LEAVE) {
        //     switch ($attribute) {
        //         case 2:
        //             # code...
        //             break;
        //         case 3:
        //             # code...
        //             break;
        //         case 4:
        //             # code...
        //             break;
        //         case 5:
        //             # code...
        //             break;
        //         case 6:
        //             # code...
        //             break;
        //         case 7:
        //             # code...
        //             break;
                
        //         default:
        //             # code...
        //             break;
        //     }
        // }
        if ($attribute['type'] == Registration::TYPE_ABSENCE) {
            $timeStart = new Carbon($attribute['time_start']);
            $timeEnd = new Carbon($attribute['time_end']);

            $countDay = $timeEnd->diffInDays($timeStart) + 1;

            for ($i = 0; $i < $countDay; $i++) {
                $currentYear[] = Carbon::parse($timeStart->toDateString())->format('Y');
                $timeStart->addDay();
            }
            $firstYear = $currentYear[0];
            $count[] = array_count_values($currentYear);

            for ($i = 0; $i < count($currentYear); $i++) {

                $total = Track::where('user_id', $userCurrent)->where('year', $currentYear[$i])->select(['annual_leave_unused'])->first();

                if (is_null($total)) {
                    $add = new Track;
                    $add->year = $currentYear[$i];
                    $add->user_id = $userCurrent;
                    $add->annual_leave_total = TrackService::calculatorYear($userCurrent, $currentYear[$i]);
                    $add->annual_leave_unused = $add->annual_leave_total;
                    if ($add->annual_leave_total == false) {
                        return false;
                    }
                    $add->save();
                }
                if ($currentYear[$i] != $firstYear) {
                    $totalNew = Track::where('user_id', $userCurrent)->where('year', $currentYear[$i])->select(['annual_leave_unused'])->first();
                    $addOldTotal = (double) $totalNew->annual_leave_unused;

                    $newResult += 1;
                } else {
                    $oldTotal = (double) $total->annual_leave_unused;

                    $result = $oldTotal - ($i + 1);
                }
            }

            if ($newResult > 0) {
                $resultForNextYear = $addOldTotal - $newResult;
                if ($result < 0 || $resultForNextYear < 0) {
                    return false;
                }
                return true;
            }

            if ($result < 0) {
                return false;
            }
            return true;

        } else {
            $time = explode(';', $attribute['date']);
            for ($i = 0; $i < count($time); $i++) {
                $timeChildren = explode(',', $time[$i]);
                $resultProcess[] = TimeAbsenceService::process($timeChildren[0], $now, $userCurrent, $timeChildren[1]);
            }
            $tempNewResult = 0;
            $tempResult = 0;
            for ($i = 0; $i < count($resultProcess); $i++) {
                $cutResultProcess = explode('-', $resultProcess[$i]);

                for ($j = 0; $j < count($cutResultProcess); $j++) {

                    $tempResult += $cutResultProcess[0];
                    $tempNewResult += $cutResultProcess[1];
                    break;
                }
            }
            $total = Track::where('user_id', $userCurrent)->where('year', $now)->select(['annual_leave_unused'])->first();
            $resultForCurrentYear = $total->annual_leave_unused - $tempResult;

            if ($tempNewResult > 0) {
                $totalNew = Track::where('user_id', $userCurrent)->where('year', $now + 1)->select(['annual_leave_unused'])->first();
                $resultForNextYear = $totalNew->annual_leave_unused - $tempNewResult;

                if ($resultForCurrentYear < 0 || $resultForNextYear < 0) {
                    return false;
                }
                return true;
            }

            if ($resultForCurrentYear < 0) {
                return false;
            }
            return true;
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
