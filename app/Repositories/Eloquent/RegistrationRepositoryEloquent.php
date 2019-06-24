<?php

namespace App\Repositories\Eloquent;

use App\Models\Registration;
use App\Presenters\RegistrationPresenter;
use App\Repositories\Contracts\RegistrationRepository;
use App\Services\TimeAbsenceService;
use Carbon\Carbon;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class RegistrationRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class RegistrationRepositoryEloquent extends BaseRepository implements RegistrationRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Registration::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return RegistrationPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getWorkdays($date1, $date2, $workSat = false, $patron = null)
    {
        if (!defined('SATURDAY')) {
            define('SATURDAY', 6);
        }

        if (!defined('SUNDAY')) {
            define('SUNDAY', 0);
        }

        // Array of all public festivities
        $publicHolidays = array('01-01', '04-30', '05-01', '09-02');
        // The Patron day (if any) is added to public festivities
        if ($patron) {
            $publicHolidays[] = $patron;
        }

        /*
         * Array of all Easter Mondays in the given interval
         */
        // $yearStart = date('Y', strtotime($date1));
        // $yearEnd = date('Y', strtotime($date2));

        // for ($i = $yearStart; $i <= $yearEnd; $i++) {
        //     $easter = date('Y-m-d', easter_date($i));
        //     list($y, $m, $g) = explode("-", $easter);
        //     $monday = mktime(0, 0, 0, date($m), date($g) + 1, date($y));
        //     $easterMondays[] = $monday;
        // }

        $start = strtotime($date1);
        $end = strtotime($date2);
        $workdays = 0;
        for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
            $day = date("w", $i); // 0=sun, 1=mon, ..., 6=sat
            $mmgg = date('m-d', $i);
            if ($day != SUNDAY &&
                !in_array($mmgg, $publicHolidays) &&

                !($day == SATURDAY && $workSat == false)) {
                $workdays++;
            }
        }

        return intval($workdays);
    }

    // public function inputTime(array $attributes) {

    // }

    public function create(array $attributes)
    {

        // $daystart = $attributes['time_off_beginning'];
        // $dayend = $attributes['time_off_ending'];
        // $holiday = $this->getWorkdays($daystart, $dayend);
        // // dd($holiday);
        // // $check = $this->model()::where('user_id', $attributes['user_id'])->exists();
        // $latest = $this->model()::where('user_id', $attributes['user_id'])
        //     ->where(function ($query) use ($daystart, $dayend) {
        //         $query->where([['time_off_beginning', '<=', $daystart], ['time_off_ending', '>=', $dayend]])
        //             ->orwhere([['time_off_beginning', '>=', $daystart], ['time_off_beginning', '<=', $dayend]])
        //             ->orwhere([['time_off_ending', '>=', $daystart], ['time_off_ending', '<=', $dayend]]);
        //     })->get();
        // $days = $latest->count();
        // $attributes['status'] = 0;
        // if($attributes['type_id'] == 1) {
        //     $attributes['annual_leave_total'] = 12 - $attributes['absence_days'];
        // }
        if ($attributes['type'] == 'From day to day') {
            // $timeend = new Carbon($attributes['time_end']);
            // $timestart = new Carbon($attributes['time_start']);
            $attributes['status'] = 3;
            if ($attributes['status'] == 3) {
                $attributes['requested_date'] = Carbon::now()->toDateString();
            }
            // $attributes['general_information'] = "Time absence of you start at : " . $timestart->toDateString() . " and end at: " . $timeend->toDateString();
            // $attributes['current_year'] = $timeend->year;
        } else {
            $attributes['status'] = 3;
            if ($attributes['status'] == 3) {
                $attributes['requested_date'] = Carbon::now()->toDateString();
            }
        }

        $res = parent::create($attributes);
        $date = $this->model()::where('user_id', $attributes['user_id'])->select('id')->get();
        $id = array();
        for ($i = 0; $i < count($date); $i++) {
            $id[] = $date[$i]->id;
        }
        $timeabsence = TimeAbsenceService::add($res['data']['id'], $attributes);
        return parent::find($res['data']['id']);
    }
}

// if($attributes['type'] == 'The specific day') {
//     $attributes['absence_days'] = 0;
// }

// TimeAbsenceService::checkTime($id, $attributes);
// dd($days);
// if ($days == 0) {
//     $start = new Carbon($attributes['time_off_beginning']);
//     $end = new Carbon($attributes['time_off_ending']);

//     if ($attributes['time_off_beginning'] == $attributes['time_off_ending']) {
//         $attributes['absence_days'] = $holiday;
//         if ($attributes['absence_days'] == 0) {
//             $attributes['general_information'] = "You have registered the same calendar on holidays or on non-working days. Registration session will not be counted.";
//             $attributes['requested_date'] = Carbon::now();
//             $attributes['status'] = 0;
//             $attributes['current_year'] = $start->year;
//         } else {
//             if ($attributes['absence_days'] == 1 && ($attributes['at_time'] == "Afternoon" || $attributes['at_time'] == "Morning")) {
//                 $attributes['absence_days'] = 0.5;
//                 if ($attributes['at_time'] == "Afternoon") {
//                     $attributes['general_information'] = "Time off is : Afternoon of " . $attributes['time_off_beginning'];
//                     $attributes['requested_date'] = Carbon::now();
//                     $attributes['status'] = 3;
//                     $attributes['current_year'] = $start->year;
//                 } else {
//                     $attributes['general_information'] = "Time off is : Morning of " . $attributes['time_off_beginning'];
//                     $attributes['requested_date'] = Carbon::now();
//                     $attributes['status'] = 3;
//                     $attributes['current_year'] = $start->year;
//                 }
//             } else {
//                 $attributes['absence_days'] == 1;
//                 $attributes['general_information'] = "Time off is : one day " . $attributes['time_off_beginning'];
//                 $attributes['requested_date'] = Carbon::now();
//                 $attributes['status'] = 3;
//                 $attributes['current_year'] = $start->year;
//             }
//         }
//     } elseif ($attributes['time_off_beginning'] != $attributes['time_off_ending']) {
//         if ($end->month == $start->month + 1 && $end->year == $start->year) {
//             if ($end->day == 1) {
//                 if ($end->month == 4 || $end->month == 6 || $end->month == 9 || $end->month == 11 || $end->month == 2 || $end->month == 8) {
//                     if (($attributes['at_time'] == "Afternoon" || $attributes['at_time'] == "Morning")) {
//                         $attributes['absence_days'] = $holiday - 0.5;
//                         if ($attributes['at_time'] == "Afternoon") {
//                             $attributes['general_information'] = "Time off start at : Afternoon of " . $attributes['time_off_beginning'];
//                             $attributes['requested_date'] = Carbon::now();
//                             $attributes['status'] = 3;
//                             $attributes['current_year'] = $end->year;
//                         } else {
//                             $attributes['general_information'] = "Time off  end at : Morning of " . $attributes['time_off_ending'];
//                             $attributes['requested_date'] = Carbon::now();
//                             $attributes['status'] = 3;
//                             $attributes['current_year'] = $end->year;
//                         }
//                     } else {
//                         $attributes['absence_days'] = $holiday;
//                         $attributes['general_information'] = "Your absence day is full day. From at " . $attributes['time_off_beginning'] . " to at " . $attributes['time_off_ending'];
//                         $attributes['requested_date'] = Carbon::now();
//                         $attributes['status'] = 3;
//                         $attributes['current_year'] = $end->year;
//                     }
//                     //day off 30 (30 - 31 - 1) {31 - 30 = 1 + 1 + 1}
//                     //day off 29 (29 - 30 - 31 - 1) {31 - 29 = 2 + 1 + 1}
//                 } elseif ($end->month == 3) {
//                     if ($end->year == 2100) {
//                         if (($attributes['at_time'] == "Afternoon" || $attributes['at_time'] == "Morning")) {
//                             $attributes['absence_days'] = $holiday - 0.5;
//                             if ($attributes['at_time'] == "Afternoon") {
//                                 $attributes['general_information'] = "Time off start at : Afternoon of " . $attributes['time_off_beginning'];
//                                 $attributes['requested_date'] = Carbon::now();
//                                 $attributes['status'] = 3;
//                                 $attributes['current_year'] = $end->year;
//                             } else {
//                                 $attributes['general_information'] = "Time off  end at : Morning of " . $attributes['time_off_ending'];
//                                 $attributes['requested_date'] = Carbon::now();
//                                 $attributes['status'] = 3;
//                                 $attributes['current_year'] = $end->year;
//                             }
//                         } else {
//                             $attributes['absence_days'] = $holiday;
//                             $attributes['general_information'] = "Your absence day is full day. From at " . $attributes['time_off_beginning'] . " to at " . $attributes['time_off_ending'];
//                             $attributes['requested_date'] = Carbon::now();
//                             $attributes['status'] = 3;
//                             $attributes['current_year'] = $end->year;
//                         }
//                         //2100 % 400 != 0;
//                     } else {
//                         if ($end->year % 4 == 0) {
//                             if (($attributes['at_time'] == "Afternoon" || $attributes['at_time'] == "Morning")) {
//                                 $attributes['absence_days'] = $holiday - 0.5;
//                                 if ($attributes['at_time'] == "Afternoon") {
//                                     $attributes['general_information'] = "Time off start at : Afternoon of " . $attributes['time_off_beginning'];
//                                     $attributes['requested_date'] = Carbon::now();
//                                     $attributes['status'] = 3;
//                                     $attributes['current_year'] = $end->year;
//                                 } else {
//                                     $attributes['general_information'] = "Time off  end at : Morning of " . $attributes['time_off_ending'];
//                                     $attributes['requested_date'] = Carbon::now();
//                                     $attributes['status'] = 3;
//                                     $attributes['current_year'] = $end->year;
//                                 }
//                             } else {
//                                 $attributes['absence_days'] = $holiday;
//                                 $attributes['general_information'] = "Your absence day is full day. From at " . $attributes['time_off_beginning'] . " to at " . $attributes['time_off_ending'];
//                                 $attributes['requested_date'] = Carbon::now();
//                                 $attributes['status'] = 3;
//                                 $attributes['current_year'] = $end->year;
//                             }
//                             //YYYY % 4 == 0 => This is Leap Year
//                         } else {
//                             if (($attributes['at_time'] == "Afternoon" || $attributes['at_time'] == "Morning")) {
//                                 $attributes['absence_days'] = $holiday - 0.5;
//                                 if ($attributes['at_time'] == "Afternoon") {
//                                     $attributes['general_information'] = "Time off start at : Afternoon of " . $attributes['time_off_beginning'];
//                                     $attributes['requested_date'] = Carbon::now();
//                                     $attributes['status'] = 3;
//                                     $attributes['current_year'] = $end->year;
//                                 } else {
//                                     $attributes['general_information'] = "Time off  end at : Morning of " . $attributes['time_off_ending'];
//                                     $attributes['requested_date'] = Carbon::now();
//                                     $attributes['status'] = 3;
//                                     $attributes['current_year'] = $end->year;
//                                 }
//                             } else {
//                                 $attributes['absence_days'] = $holiday;
//                                 $attributes['general_information'] = "Your absence day is full day. From at " . $attributes['time_off_beginning'] . " to at " . $attributes['time_off_ending'];
//                                 $attributes['requested_date'] = Carbon::now();
//                                 $attributes['status'] = 3;
//                                 $attributes['current_year'] = $end->year;
//                             }
//                         }
//                     }
//                 } else {
//                     if (($attributes['at_time'] == "Afternoon" || $attributes['at_time'] == "Morning")) {
//                         $attributes['absence_days'] = $holiday - 0.5;
//                         if ($attributes['at_time'] == "Afternoon") {
//                             $attributes['general_information'] = "Time off start at : Afternoon of " . $attributes['time_off_beginning'];
//                             $attributes['requested_date'] = Carbon::now();
//                             $attributes['status'] = 3;
//                             $attributes['current_year'] = $end->year;
//                         } else {
//                             $attributes['general_information'] = "Time off  end at : Morning of " . $attributes['time_off_ending'];
//                             $attributes['requested_date'] = Carbon::now();
//                             $attributes['status'] = 3;
//                             $attributes['current_year'] = $end->year;
//                         }
//                     } else {
//                         $attributes['absence_days'] = $holiday;
//                         $attributes['general_information'] = "Your absence day is full day. From at " . $attributes['time_off_beginning'] . " to at " . $attributes['time_off_ending'];
//                         $attributes['requested_date'] = Carbon::now();
//                         $attributes['status'] = 3;
//                         $attributes['current_year'] = $end->year;
//                     }
//                 }
//             } elseif ($end->day > 1 && $end->day <= 15) {
//                 if ($end->month == 4 || $end->month == 6 || $end->month == 9 || $end->month == 11 || $end->month == 2 || $end->month == 8) {
//                     $sum_timeoff = $holiday;
//                     if ($sum_timeoff >= 15) {
//                         return "time_invalid_1";
//                     } else {
//                         if (($attributes['at_time'] == "Afternoon" || $attributes['at_time'] == "Morning")) {
//                             $attributes['absence_days'] = $holiday - 0.5;
//                             if ($attributes['at_time'] == "Afternoon") {
//                                 $attributes['general_information'] = "Time off start at : Afternoon of " . $attributes['time_off_beginning'];
//                                 $attributes['requested_date'] = Carbon::now();
//                                 $attributes['status'] = 3;
//                                 $attributes['current_year'] = $end->year;
//                             } else {
//                                 $attributes['general_information'] = "Time off  end at : Morning of " . $attributes['time_off_ending'];
//                                 $attributes['requested_date'] = Carbon::now();
//                                 $attributes['status'] = 3;
//                                 $attributes['current_year'] = $end->year;
//                             }
//                         } else {
//                             $attributes['absence_days'] = $holiday;
//                             $attributes['general_information'] = "Your absence day is full day. From at " . $attributes['time_off_beginning'] . " to at " . $attributes['time_off_ending'];
//                             $attributes['requested_date'] = Carbon::now();
//                             $attributes['status'] = 3;
//                             $attributes['current_year'] = $end->year;
//                         }
//                     }
//                 } elseif ($end->month == 3) {
//                     if ($end->year == '2100') {
//                         $sum_timeoff = $holiday;
//                         if ($sum_timeoff >= 15) {
//                             return "time_invalid_1";
//                         } else {
//                             if (($attributes['at_time'] == "Afternoon" || $attributes['at_time'] == "Morning")) {
//                                 $attributes['absence_days'] = $holiday - 0.5;
//                                 if ($attributes['at_time'] == "Afternoon") {
//                                     $attributes['general_information'] = "Time off start at : Afternoon of " . $attributes['time_off_beginning'];
//                                     $attributes['requested_date'] = Carbon::now();
//                                     $attributes['status'] = 3;
//                                     $attributes['current_year'] = $end->year;
//                                 } else {
//                                     $attributes['general_information'] = "Time off  end at : Morning of " . $attributes['time_off_ending'];
//                                     $attributes['requested_date'] = Carbon::now();
//                                     $attributes['status'] = 3;
//                                     $attributes['current_year'] = $end->year;
//                                 }
//                             } else {
//                                 $attributes['absence_days'] = $holiday;
//                                 $attributes['general_information'] = "Your absence day is full day. From at " . $attributes['time_off_beginning'] . " to at " . $attributes['time_off_ending'];
//                                 $attributes['requested_date'] = Carbon::now();
//                                 $attributes['status'] = 3;
//                                 $attributes['current_year'] = $end->year;
//                             }
//                         }
//                     } else {
//                         if ($end->year % 4 == 0) {
//                             //YYYY % 4 == 0 => This is Leap Year
//                             $sum_timeoff = $holiday;
//                             if ($sum_timeoff >= 15) {
//                                 return "time_invalid_1";
//                             } else {
//                                 if (($attributes['at_time'] == "Afternoon" || $attributes['at_time'] == "Morning")) {
//                                     $attributes['absence_days'] = $holiday - 0.5;
//                                     if ($attributes['at_time'] == "Afternoon") {
//                                         $attributes['general_information'] = "Time off start at : Afternoon of " . $attributes['time_off_beginning'];
//                                         $attributes['requested_date'] = Carbon::now();
//                                         $attributes['status'] = 3;
//                                         $attributes['current_year'] = $end->year;
//                                     } else {
//                                         $attributes['general_information'] = "Time off  end at : Morning of " . $attributes['time_off_ending'];
//                                         $attributes['requested_date'] = Carbon::now();
//                                         $attributes['status'] = 3;
//                                         $attributes['current_year'] = $end->year;
//                                     }
//                                 } else {
//                                     $attributes['absence_days'] = $holiday;
//                                     $attributes['general_information'] = "Your absence day is full day. From at " . $attributes['time_off_beginning'] . " to at " . $attributes['time_off_ending'];
//                                     $attributes['requested_date'] = Carbon::now();
//                                     $attributes['status'] = 3;
//                                     $attributes['current_year'] = $end->year;
//                                 }
//                             }
//                         } else {
//                             $sum_timeoff = $holiday;
//                             if ($sum_timeoff >= 15) {
//                                 return "time_invalid_1";
//                             } else {
//                                 if (($attributes['at_time'] == "Afternoon" || $attributes['at_time'] == "Morning")) {
//                                     $attributes['absence_days'] = $holiday - 0.5;
//                                     if ($attributes['at_time'] == "Afternoon") {
//                                         $attributes['general_information'] = "Time off start at : Afternoon of " . $attributes['time_off_beginning'];
//                                         $attributes['requested_date'] = Carbon::now();
//                                         $attributes['status'] = 3;
//                                         $attributes['current_year'] = $end->year;
//                                     } else {
//                                         $attributes['general_information'] = "Time off  end at : Morning of " . $attributes['time_off_ending'];
//                                         $attributes['requested_date'] = Carbon::now();
//                                         $attributes['status'] = 3;
//                                         $attributes['current_year'] = $end->year;
//                                     }
//                                 } else {
//                                     $attributes['absence_days'] = $holiday;
//                                     $attributes['general_information'] = "Your absence day is full day. From at " . $attributes['time_off_beginning'] . " to at " . $attributes['time_off_ending'];
//                                     $attributes['requested_date'] = Carbon::now();
//                                     $attributes['status'] = 3;
//                                     $attributes['current_year'] = $end->year;
//                                 }
//                             }
//                         }
//                     }
//                 } else {
//                     $sum_timeoff = $holiday;
//                     if ($sum_timeoff >= 15) {
//                         return "time_invalid_1";
//                     } else {
//                         if (($attributes['at_time'] == "Afternoon" || $attributes['at_time'] == "Morning")) {
//                             $attributes['absence_days'] = $holiday - 0.5;
//                             if ($attributes['at_time'] == "Afternoon") {
//                                 $attributes['general_information'] = "Time off start at : Afternoon of " . $attributes['time_off_beginning'];
//                                 $attributes['requested_date'] = Carbon::now();
//                                 $attributes['status'] = 3;
//                                 $attributes['current_year'] = $end->year;
//                             } else {
//                                 $attributes['general_information'] = "Time off  end at : Morning of " . $attributes['time_off_ending'];
//                                 $attributes['requested_date'] = Carbon::now();
//                                 $attributes['status'] = 3;
//                                 $attributes['current_year'] = $end->year;
//                             }
//                         } else {
//                             $attributes['absence_days'] = $holiday;
//                             $attributes['general_information'] = "Your absence day is full day. From at " . $attributes['time_off_beginning'] . " to at " . $attributes['time_off_ending'];
//                             $attributes['requested_date'] = Carbon::now();
//                             $attributes['status'] = 3;
//                             $attributes['current_year'] = $end->year;
//                         }
//                     }
//                 }
//             } else {
//                 return 'error';
//             }
//         } elseif ($end->month == $start->month && $end->year == $start->year) {
