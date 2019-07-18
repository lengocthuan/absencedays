<?php
namespace App\Services;

use App\Models\TimeAbsence;
use App\Models\Track;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TrackService
{
    public static function update($id, array $attribute)
    {
        $user_id = Auth::id();
        $year = Carbon::now()->format('Y');
        $check = Track::where('user_id', $user_id)->where('year', $year)->first();

        if($check->annual_leave_total == null) {
            $addTime = TrackService::calculatorYear($user_id);
            $check->annual_leave_total = $addTime;
            $check->save();
        }

        $trackId = Track::where(['user_id' => $attribute['user_id'], 'year' => $year])->get();
        $track = Track::find($trackId[0]->id);
        $time = TimeAbsence::where('registration_id', $id)->select('time_details', 'absence_days')->get();

        // $newOff = 0;
        $arrayMonth = array();
        $arrayYear = array();

        for ($i = 0; $i < count($time); $i++) {
            $month = Carbon::parse($time[$i]->time_details)->format('m');
            $year1 = Carbon::parse($time[$i]->time_details)->format('Y'); //this is year;
            $arrayMonth[] = $month; //12 01 01              01        12
            $arrayYear[] = $year1; //2019 2020 2020 2020 2019
        }

        //check year and month and calculator sum;
        for ($i = 0; $i < count($arrayYear); $i++) {
            if ($arrayYear[$i] == $year) {
                switch ($arrayMonth[$i]) {
                    case "01":
                        $newOff = $track->January;
                        $track->January = $newOff + $time[$i]->absence_days;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "02":
                        $newOff = $track->February;
                        $track->February = $newOff + $time[$i]->absence_days;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "03":
                        $newOff = $track->March;
                        $track->March = $newOff + $time[$i]->absence_days;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "04":
                        $newOff = $track->April;
                        $track->April = $newOff + $time[$i]->absence_days;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "05":
                        $newOff = $track->May;
                        $track->May = $newOff +$time[$i]->absence_days;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "06":
                        $newOff = $track->June;
                        $track->June = $newOff +$time[$i]->absence_days;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "07":
                        $newOff = $track->July;
                        $track->July = $newOff + $time[$i]->absence_days;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "08":
                        $newOff = $track->August;
                        $track->August = $newOff + $time[$i]->absence_days;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "09":
                        $newOff = $track->September;
                        $track->September = $newOff + $time[$i]->absence_days;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "10":
                        $newOff = $track->October;
                        $track->October = $newOff + $time[$i]->absence_days;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "11":
                        $newOff = $track->November;
                        $track->November = $newOff + $time[$i]->absence_days;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;
                    case "12":
                        $newOff = $track->December;
                        $track->December = $newOff + $time[$i]->absence_days;
                        $track->annual_leave_unused = $track->annual_leave_total - ($track->January + $track->February + $track->March + $track->April + $track->May + $track->June + $track->July + $track->August + $track->September + $track->October + $track->November + $track->December);
                        $track->save();
                        break;

                    default:
                        break;
                }
            } elseif ($arrayYear[$i] != $year) {
                $check = Track::where('year', $arrayYear[$i])->get();
                if (count($check) == 0) {
                    $add = new Track;
                    $add->year = $year + 1;
                    $add->user_id = $attribute['user_id'];
                    $add->annual_leave_total = $addTime;
                    // $timeOff = 0;
                    switch ($arrayMonth[$i]) {
                        case "01":
                            $timeOff = $add->January;
                            $add->January = $timeOff + $time[$i]->absence_days;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "02":
                            $timeOff = $add->February;
                            $add->February = $timeOff + $time[$i]->absence_days;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "03":
                            $timeOff = $add->March;
                            $add->March = $timeOff + $time[$i]->absence_days;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "04":
                            $timeOff = $add->April;
                            $add->April = $timeOff + $time[$i]->absence_days;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "05":
                            $timeOff = $add->May;
                            $add->May = $timeOff + $time[$i]->absence_days;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "06":
                            $timeOff = $add->June;
                            $add->June = $timeOff + $time[$i]->absence_days;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "07":
                            $timeOff = $add->July;
                            $add->July = $timeOff + $time[$i]->absence_days;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "08":
                            $timeOff = $add->August;
                            $add->August = $timeOff + $time[$i]->absence_days;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "09":
                            $timeOff = $add->September;
                            $add->September = $timeOff + $time[$i]->absence_days;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "10":
                            $timeOff = $add->October;
                            $add->October = $timeOff + $time[$i]->absence_days;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "11":
                            $timeOff = $add->November;
                            $add->November = $timeOff + $time[$i]->absence_days;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;
                        case "12":
                            $timeOff = $add->December;
                            $add->December = $timeOff + $time[$i]->absence_days;
                            $add->annual_leave_unused = $add->annual_leave_total - ($add->January + $add->February + $add->March + $add->April + $add->May + $add->June + $add->July + $add->August + $add->September + $add->October + $add->November + $add->December);
                            $add->save();
                            break;

                        default:
                            break;
                    }

                } else {
                    $newTrack = Track::find($check[0]->id);
                    $timeOff = 0;
                    switch ($arrayMonth[$i]) {
                        case "01":
                            $timeOff = $newTrack->January;
                            $newTrack->January = $timeOff + $time[$i]->absence_days;
                            $newTrack->annual_leave_unused = $newTrack->annual_leave_total - ($newTrack->January + $newTrack->February + $newTrack->March + $newTrack->April + $newTrack->May + $newTrack->June + $newTrack->July + $newTrack->August + $newTrack->September + $newTrack->October + $newTrack->November + $newTrack->December);
                            $newTrack->save();
                            break;
                        case "02":
                            $timeOff = $newTrack->February;
                            $newTrack->February = $timeOff + $time[$i]->absence_days;
                            $newTrack->annual_leave_unused = $newTrack->annual_leave_total - ($newTrack->January + $newTrack->February + $newTrack->March + $newTrack->April + $newTrack->May + $newTrack->June + $newTrack->July + $newTrack->August + $newTrack->September + $newTrack->October + $newTrack->November + $newTrack->December);
                            $newTrack->save();
                            break;
                        case "03":
                            $timeOff = $newTrack->March;
                            $newTrack->March = $timeOff + $time[$i]->absence_days;
                            $newTrack->annual_leave_unused = $newTrack->annual_leave_total - ($newTrack->January + $newTrack->February + $newTrack->March + $newTrack->April + $newTrack->May + $newTrack->June + $newTrack->July + $newTrack->August + $newTrack->September + $newTrack->October + $newTrack->November + $newTrack->December);
                            $newTrack->save();
                            break;
                        case "04":
                            $timeOff = $newTrack->April;
                            $newTrack->April = $timeOff + $time[$i]->absence_days;
                            $newTrack->annual_leave_unused = $newTrack->annual_leave_total - ($newTrack->January + $newTrack->February + $newTrack->March + $newTrack->April + $newTrack->May + $newTrack->June + $newTrack->July + $newTrack->August + $newTrack->September + $newTrack->October + $newTrack->November + $newTrack->December);
                            $newTrack->save();
                            break;
                        case "05":
                            $timeOff = $newTrack->May;
                            $newTrack->May = $timeOff + $time[$i]->absence_days;
                            $newTrack->annual_leave_unused = $newTrack->annual_leave_total - ($newTrack->January + $newTrack->February + $newTrack->March + $newTrack->April + $newTrack->May + $newTrack->June + $newTrack->July + $newTrack->August + $newTrack->September + $newTrack->October + $newTrack->November + $newTrack->December);
                            $newTrack->save();
                            break;
                        case "06":
                            $timeOff = $newTrack->June;
                            $newTrack->June = $timeOff + $time[$i]->absence_days;
                            $newTrack->annual_leave_unused = $newTrack->annual_leave_total - ($newTrack->January + $newTrack->February + $newTrack->March + $newTrack->April + $newTrack->May + $newTrack->June + $newTrack->July + $newTrack->August + $newTrack->September + $newTrack->October + $newTrack->November + $newTrack->December);
                            $newTrack->save();
                            break;
                        case "07":
                            $timeOff = $newTrack->July;
                            $newTrack->July = $timeOff + $time[$i]->absence_days;
                            $newTrack->annual_leave_unused = $newTrack->annual_leave_total - ($newTrack->January + $newTrack->February + $newTrack->March + $newTrack->April + $newTrack->May + $newTrack->June + $newTrack->July + $newTrack->August + $newTrack->September + $newTrack->October + $newTrack->November + $newTrack->December);
                            $newTrack->save();
                            break;
                        case "08":
                            $timeOff = $newTrack->August;
                            $newTrack->August = $timeOff + $time[$i]->absence_days;
                            $newTrack->annual_leave_unused = $newTrack->annual_leave_total - ($newTrack->January + $newTrack->February + $newTrack->March + $newTrack->April + $newTrack->May + $newTrack->June + $newTrack->July + $newTrack->August + $newTrack->September + $newTrack->October + $newTrack->November + $newTrack->December);
                            $newTrack->save();
                            break;
                        case "09":
                            $timeOff = $newTrack->September;
                            $newTrack->September = $timeOff + $time[$i]->absence_days;
                            $newTrack->annual_leave_unused = $newTrack->annual_leave_total - ($newTrack->January + $newTrack->February + $newTrack->March + $newTrack->April + $newTrack->May + $newTrack->June + $newTrack->July + $newTrack->August + $newTrack->September + $newTrack->October + $newTrack->November + $newTrack->December);
                            $newTrack->save();
                            break;
                        case "10":
                            $timeOff = $newTrack->October;
                            $newTrack->October = $timeOff + $time[$i]->absence_days;
                            $newTrack->annual_leave_unused = $newTrack->annual_leave_total - ($newTrack->January + $newTrack->February + $newTrack->March + $newTrack->April + $newTrack->May + $newTrack->June + $newTrack->July + $newTrack->August + $newTrack->September + $newTrack->October + $newTrack->November + $newTrack->December);
                            $newTrack->save();
                            break;
                        case "11":
                            $timeOff = $newTrack->November;
                            $newTrack->November = $timeOff + $time[$i]->absence_days;
                            $newTrack->annual_leave_unused = $newTrack->annual_leave_total - ($newTrack->January + $newTrack->February + $newTrack->March + $newTrack->April + $newTrack->May + $newTrack->June + $newTrack->July + $newTrack->August + $newTrack->September + $newTrack->October + $newTrack->November + $newTrack->December);
                            $newTrack->save();
                            break;
                        case "12":
                            $timeOff = $newTrack->December;
                            $newTrack->December = $timeOff + $time[$i]->absence_days;
                            $newTrack->annual_leave_unused = $newTrack->annual_leave_total - ($newTrack->January + $newTrack->February + $newTrack->March + $newTrack->April + $newTrack->May + $newTrack->June + $newTrack->July + $newTrack->August + $newTrack->September + $newTrack->October + $newTrack->November + $newTrack->December);
                            $newTrack->save();
                            break;

                        default:
                            break;
                    }
                }
            }
        }
    }

    public static function create($id)
    {
        $addTime = TrackService::calculatorYear($id);
        $check = Track::where('user_id', $id)->get();
        if (empty($check[0]->user_id)) {
            $new = new Track;
            $new->year = Carbon::now()->format('Y');
            $new->user_id = $id;
            $new->annual_leave_total = $addTime;
            $new->save();
        }
    }

    public static function calculatorYear($attribute)
    {
        $user = User::where('id', $attribute)->select('first_workday')->get();
        $firstWorkday = new Carbon($user[0]->first_workday);
        $firstWorkday1 = $firstWorkday->toDateString();
        $annualLeaveTotal = Carbon::parse($firstWorkday1)->age; //Calculate the total number of working years
        $yearAnnual = 12;
        if ($annualLeaveTotal >= 5) {
            for ($i = 0; $i <= $annualLeaveTotal; $i += 5) {
                $addTime = $yearAnnual++;
            }
        } else {
            $addTime = $yearAnnual;
        }

        return $addTime;
    }

}
