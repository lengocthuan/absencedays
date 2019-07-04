<?php
namespace App\Services;

use App\Models\TimeAbsence;
use App\Models\Track;
use App\User;
use Carbon\Carbon;

class TrackService
{
    public static function update($id, array $attribute)
    {
        // dd('ohan');
        $user = User::where('id', $attribute['user_id'])->select('first_workday')->get();
        $first_workday = new Carbon($user[0]->first_workday);
        $first_workday1 = $first_workday->toDateString();
        $annual_leave_total = Carbon::parse($first_workday1)->age; //Calculate the total number of working years
        // dd($annual_leave_total);
        $year_annual = 12;
        if ($annual_leave_total >= 5) {
            for ($i = 0; $i <= $annual_leave_total; $i += 5) {
                $addtime = $year_annual++;
            }
        } else {
            $addtime = $year_annual;
        }

        $year = Carbon::now()->format('Y');
        $track_id = Track::where(['user_id' => $attribute['user_id'], 'year' => $year])->get();
        // dd($track_id);
        $track = Track::find($track_id[0]->id);
        $time = TimeAbsence::where('registration_id', $id)->select('time_details', 'absence_days')->get();
        $newoff = 0;
        $arr = array();
        $arr1 = array();
        $arr2 = array();
        for ($i = 0; $i < count($time); $i++) {
            $month = (new Carbon($time[$i]->time_details))->format('m');
            $year1 = (new Carbon($time[$i]->time_details))->format('Y');
            $arr[] = $month; //12 01 01              01        12
            $arr1[] = $year1; //2019 2020 2020 2020 2019
        }

        //
        for ($i = 0; $i < count($arr1); $i++) {
            if ($arr1[$i] == $year) {
                switch ($arr[$i]) {
                    case "01":
                        $newoff += $time[$i]->absence_days;
                        $track->January += $newoff;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "02":
                        $newoff += $time[$i]->absence_days;
                        $track->February += $newoff;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "03":
                        $newoff += $time[$i]->absence_days;
                        $track->March += $newoff;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "04":
                        $newoff += $time[$i]->absence_days;
                        $track->April += $newoff;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "05":
                        $newoff += $time[$i]->absence_days;
                        $track->May += $newoff;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "06":
                        $newoff += $time[$i]->absence_days;
                        $track->June += $newoff;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "07":
                        $newoff += $time[$i]->absence_days;
                        $track->July += $newoff;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "08":
                        $newoff += $time[$i]->absence_days;
                        $track->August += $newoff;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "09":
                        $newoff += $time[$i]->absence_days;
                        $track->September += $newoff;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "10":
                        $newoff += $time[$i]->absence_days;
                        $track->October += $newoff;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "11":
                        $newoff += $time[$i]->absence_days;
                        $track->November += $newoff;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "12":
                        $newoff += $time[$i]->absence_days;
                        $track->December += $newoff;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;

                    default:
                        return 'Invalid';
                        break;
                }
            } elseif ($arr1[$i] != $year) {
                $check = Track::where('year', $arr1[$i])->get();
                if (count($check) == 0) {
                    $add = new Track;
                    $add->year = $year + 1;
                    $add->user_id = $attribute['user_id'];
                    $add->annual_leave_total = $addtime;
                    $timeoff = 0;
                    switch ($arr[$i]) {
                        case "01":
                            $timeoff += $time[$i]->absence_days;
                            $add->January += $timeoff;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "02":
                            $timeoff += $time[$i]->absence_days;
                            $add->February += $timeoff;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "03":
                            $timeoff += $time[$i]->absence_days;
                            $add->March += $timeoff;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "04":
                            $timeoff += $time[$i]->absence_days;
                            $add->April += $timeoff;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "05":
                            $timeoff += $time[$i]->absence_days;
                            $add->May += $timeoff;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "06":
                            $timeoff += $time[$i]->absence_days;
                            $add->June += $timeoff;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "07":
                            $timeoff += $time[$i]->absence_days;
                            $add->July += $timeoff;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "08":
                            $timeoff += $time[$i]->absence_days;
                            $add->August += $timeoff;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "09":
                            $timeoff += $time[$i]->absence_days;
                            $add->September += $timeoff;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "10":
                            $timeoff += $time[$i]->absence_days;
                            $add->October += $timeoff;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "11":
                            $timeoff += $time[$i]->absence_days;
                            $add->November += $timeoff;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "12":
                            $timeoff += $time[$i]->absence_days;
                            $add->December += $timeoff;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;

                        default:
                            return 'Invalid1';
                            break;
                    }

                } else {
                    $newtrack = Track::find($check[0]->id);
                    $timeoff = 0;
                    switch ($arr[$i]) {
                        case "01":
                            $timeoff += $time[$i]->absence_days;
                            $newtrack->January += $timeoff;
                            $newtrack->annual_leave_unused = $newtrack->annual_leave_total - ($newtrack->January + $newtrack->February + $newtrack->March + $newtrack->April + $newtrack->May + $newtrack->June + $newtrack->July + $newtrack->August + $newtrack->September + $newtrack->October + $newtrack->November + $newtrack->December);
                            $newtrack->save();
                            break;
                        case "02":
                            $timeoff += $time[$i]->absence_days;
                            $check->February = $timeoff;
                            $newtrack->annual_leave_unused = $newtrack->annual_leave_total - ($newtrack->January + $newtrack->February + $newtrack->March + $newtrack->April + $newtrack->May + $newtrack->June + $newtrack->July + $newtrack->August + $newtrack->September + $newtrack->October + $newtrack->November + $newtrack->December);
                            $newtrack->save();
                            break;
                        case "03":
                            $timeoff += $time[$i]->absence_days;
                            $check->March = $timeoff;
                            $newtrack->annual_leave_unused = $newtrack->annual_leave_total - ($newtrack->January + $newtrack->February + $newtrack->March + $newtrack->April + $newtrack->May + $newtrack->June + $newtrack->July + $newtrack->August + $newtrack->September + $newtrack->October + $newtrack->November + $newtrack->December);
                            $newtrack->save();
                            break;
                        case "04":
                            $timeoff += $time[$i]->absence_days;
                            $check->April = $timeoff;
                            $newtrack->annual_leave_unused = $newtrack->annual_leave_total - ($newtrack->January + $newtrack->February + $newtrack->March + $newtrack->April + $newtrack->May + $newtrack->June + $newtrack->July + $newtrack->August + $newtrack->September + $newtrack->October + $newtrack->November + $newtrack->December);
                            $newtrack->save();
                            break;
                        case "05":
                            $timeoff += $time[$i]->absence_days;
                            $check->May = $timeoff;
                            $newtrack->annual_leave_unused = $newtrack->annual_leave_total - ($newtrack->January + $newtrack->February + $newtrack->March + $newtrack->April + $newtrack->May + $newtrack->June + $newtrack->July + $newtrack->August + $newtrack->September + $newtrack->October + $newtrack->November + $newtrack->December);
                            $newtrack->save();
                            break;
                        case "06":
                            $timeoff += $time[$i]->absence_days;
                            $check->June = $timeoff;
                            $newtrack->annual_leave_unused = $newtrack->annual_leave_total - ($newtrack->January + $newtrack->February + $newtrack->March + $newtrack->April + $newtrack->May + $newtrack->June + $newtrack->July + $newtrack->August + $newtrack->September + $newtrack->October + $newtrack->November + $newtrack->December);
                            $newtrack->save();
                            break;
                        case "07":
                            $timeoff += $time[$i]->absence_days;
                            $check->July = $timeoff;
                            $newtrack->annual_leave_unused = $newtrack->annual_leave_total - ($newtrack->January + $newtrack->February + $newtrack->March + $newtrack->April + $newtrack->May + $newtrack->June + $newtrack->July + $newtrack->August + $newtrack->September + $newtrack->October + $newtrack->November + $newtrack->December);
                            $newtrack->save();
                            break;
                        case "08":
                            $timeoff += $time[$i]->absence_days;
                            $check->August = $timeoff;
                            $newtrack->annual_leave_unused = $newtrack->annual_leave_total - ($newtrack->January + $newtrack->February + $newtrack->March + $newtrack->April + $newtrack->May + $newtrack->June + $newtrack->July + $newtrack->August + $newtrack->September + $newtrack->October + $newtrack->November + $newtrack->December);
                            $newtrack->save();
                            break;
                        case "09":
                            $timeoff += $time[$i]->absence_days;
                            $check->September = $timeoff;
                            $newtrack->annual_leave_unused = $newtrack->annual_leave_total - ($newtrack->January + $newtrack->February + $newtrack->March + $newtrack->April + $newtrack->May + $newtrack->June + $newtrack->July + $newtrack->August + $newtrack->September + $newtrack->October + $newtrack->November + $newtrack->December);
                            $newtrack->save();
                            break;
                        case "10":
                            $timeoff += $time[$i]->absence_days;
                            $check->October = $timeoff;
                            $newtrack->annual_leave_unused = $newtrack->annual_leave_total - ($newtrack->January + $newtrack->February + $newtrack->March + $newtrack->April + $newtrack->May + $newtrack->June + $newtrack->July + $newtrack->August + $newtrack->September + $newtrack->October + $newtrack->November + $newtrack->December);
                            $newtrack->save();
                            break;
                        case "11":
                            $timeoff += $time[$i]->absence_days;
                            $check->November = $timeoff;
                            $newtrack->annual_leave_unused = $newtrack->annual_leave_total - ($newtrack->January + $newtrack->February + $newtrack->March + $newtrack->April + $newtrack->May + $newtrack->June + $newtrack->July + $newtrack->August + $newtrack->September + $newtrack->October + $newtrack->November + $newtrack->December);
                            $newtrack->save();
                            break;
                        case "12":
                            $timeoff += $time[$i]->absence_days;
                            $check->December = $timeoff;
                            $newtrack->annual_leave_unused = $newtrack->annual_leave_total - ($newtrack->January + $newtrack->February + $newtrack->March + $newtrack->April + $newtrack->May + $newtrack->June + $newtrack->July + $newtrack->August + $newtrack->September + $newtrack->October + $newtrack->November + $newtrack->December);
                            $newtrack->save();
                            break;

                        default:
                            return 'Invalid2';
                            break;
                    }
                }
            }
        }
    }

    public static function create($id)
    {
        $user = User::where('id', $id)->select('first_workday')->get();
        $first_workday = new Carbon($user[0]->first_workday);
        $first_workday1 = $first_workday->toDateString();
        $annual_leave_total = Carbon::parse($first_workday1)->age; //Calculate the total number of working years
        // dd($annual_leave_total);
        $year = 12;
        if ($annual_leave_total >= 5) {
            for ($i = 0; $i <= $annual_leave_total; $i += 5) {
                $add = $year++;
            }
        } else {
            $add = $year;
        }

        $check = Track::where('user_id', $id)->get();
        if (empty($check[0]->user_id)) {
            $new = new Track;
            $new->year = Carbon::now()->format('Y');
            $new->user_id = $id;
            $new->annual_leave_total = $add;
            $new->save();
        }
    }

}
