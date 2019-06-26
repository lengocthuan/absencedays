<?php

namespace App\Repositories\Eloquent;

use App\Models\TimeAbsence;
use App\Presenters\TimeAbsencePresenter;
use App\Repositories\Contracts\TimeAbsenceRepository;
use App\Services\TimeAbsenceService;
use Carbon\Carbon;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class TimeAbsenceRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class TimeAbsenceRepositoryEloquent extends BaseRepository implements TimeAbsenceRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TimeAbsence::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return TimeAbsencePresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getWorkdays($date1, $date2, $workSat = true, $patron = null)
    {
        if (!defined('SATURDAY')) {
            define('SATURDAY', 6);
        }

        // Array of all public festivities
        $publicHolidays = array('01-01', '04-30', '05-01', '09-02');
        // The Patron day (if any) is added to public festivities
        if ($patron) {
            $publicHolidays[] = $patron;
        }

        $start = strtotime($date1);
        $end = strtotime($date2);
        $workdays = 0;
        for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
            $day = date("w", $i); // 0=sun, 1=mon, ..., 6=sat
            $mmgg = date('m-d', $i);
            if (
                !in_array($mmgg, $publicHolidays) &&

                !($day == SATURDAY && $workSat == false)) {
                $workdays++;
            }
        }
        return intval($workdays);
    }

    // public function create(array $attributes)
    // {
    //     $daystart = "";
    //     $dayend = "";
    //     $array = array();
    //     $arr = array();
    //     // dd($dayoff);
    //     // dd($attributes['date']);
    //     if (empty($attributes['date'])) {
    //         if ($attributes['type'] == "From day to day") {
    //             $daystart = $attributes['time_start'];
    //             $dayend = $attributes['time_end'];
    //             if (!empty($daystart) && !empty($dayend)) {
    //                 $daystart = $attributes['time_start'];
    //                 $dayend = $attributes['time_end'];
    //                 $start = new Carbon($attributes['time_start']);
    //                 $end = new Carbon($attributes['time_end']);
    //                 $sum = $end->day - $start->day + 1;
    //                 $dayoff = $this->getWorkdays($daystart, $dayend);
    //             }
    //             // $date1 = Registration::where('user_id', )
    //             // $lastest = Registration::where('id', $attributes['registration_id'])->first();
    //             // $lastest1 = Registration::where('user_id', $lastest->user_id)->get();
    //             // foreach ($lastest1 as $val) {
    //             //     if ($val->id != $lastest->id) {
    //             //         $arr[] = $val->id;
    //             //     }
    //             // }
    //             // dd($arr);
    //             // $id = explode(' ', $arr);
    //             // dd($id);
    //             // $lastest3 = TimeAbsence::where('registration_id', $lastest1)->get();
    //             // dd($lastest3);
    //             // $days = $lastest1->count();
    //             // dd($days);
    //             // dd("abc");

    //             $latest = $this->model()::where('registration_id', $attributes['registration_id'])
    //                 ->where(function ($query) use ($daystart, $dayend) {
    //                     $query->where([['time_start', '<=', $daystart], ['time_end', '>=', $dayend]])
    //                         ->orwhere([['time_start', '>=', $daystart], ['time_start', '<=', $dayend]])
    //                         ->orwhere([['time_end', '>=', $daystart], ['time_end', '<=', $dayend]]);
    //                 })->get();
    //             $days = $latest->count();
    //             // dd($days);
    //             if ($days == 0) {
    //                 // dd('abcs');
    //                 if ($daystart == $dayend) {
    //                     // dd('hoply');
    //                     TimeAbsenceService::add($attributes['registration_id'], $sum);
    //                     // $timeAbsence = parent::create($attributes);
    //                     // return $timeAbsence;
    //                 } elseif ($daystart != $dayend) {
    //                     // dd('khonghoply');
    //                     if ($dayoff <= 15) {
    //                         TimeAbsenceService::add($attributes['registration_id'], $dayoff);
    //                         // $timeAbsence = parent::create($attributes);
    //                         // return $timeAbsence;
    //                         // dd($dayoff);
    //                     } else {
    //                         return "Over";
    //                         // dd('over');
    //                     }
    //                 }
    //                 $timeAbsence = parent::create($attributes);
    //                 return $timeAbsence;
    //             } else {
    //                 // dd('dvcf');
    //                 return 'Invalidate';
    //             }

    //         }
    //     } else {
    //         //if($attributes['type'] == "The specific day")
    //         $regis = 0;
    //         $time = explode(';', $attributes['date']);
    //         for ($i = 0; $i < count($time); $i++) {
    //             $time_children = explode(',', $time[$i]);
    //             // dd($time_children);
    //             $details = new TimeAbsence;
    //             $details->registration_id = $time_children[0];
    //             $regis = $time_children[0];
    //             $details->type = $time_children[1];
    //             $details->time_details = $time_children[2];
    //             $details->at_time = $time_children[3];
    //             // $details->save();
    //             // $array[]=  $time_children[2] . $time_children[3];
    //             array_push($array, $time_children[2], $time_children[3]);

    //         }
    //         // print_r($array);
    //         for ($i = 0; $i < count($array); $i++) {
    //             var_dump($array[$i]);
    //             echo " ";
    //         }
    //         // dd($array);
    //         // dd($time_children);
    //         // dd($regis);

    //         // dd($time);
    //         // $cut = explode(',', $details);
    //         // dd($cut);
    //         // for ($i = 0; $i < count($time); $i++) {
    //         //     $time_children = explode(',', $time[$i]);
    //         //     $details = new TimeAbsence;
    //         //     dd($details);
    //         // }
    //         // if($attributes['date']->registration_id == 100) {
    //         //     echo 'abc';
    //         // }
    //         // $daystart =
    //         $latest = TimeAbsence::where('registration_id', $regis)->whereIn('type', ['From day to day', 'The specific day'])->get();
    //         dd($latest);
    //         //     ->where(function ($query) use ($type, $daystart, $dayend) {
    //         //         $query->where([['time_start', '<=', $daystart], ['time_end', '>=', $dayend]])
    //         //             ->orwhere([['time_start', '>=', $daystart], ['time_start', '<=', $dayend]])
    //         //             ->orwhere([['time_end', '>=', $daystart], ['time_end', '<=', $dayend]]);
    //         //     })->get();
    //         // $days = $latest->count();
    //         // if ($days == 0) {
    //         // dd('tuan');

    //         // TimeAbsenceService::addTime($attributes['date']); //recived string from FE;

    //         // } else {
    //         //     // return 'Invalidate';
    //         //     dd('tuan1');
    //         // }

    //     }
    //     // else {
    //     //     // dd('khongcogi');
    //     //     return "InvalidateType";
    //     // }

    // }
    // public function search(array $attributes) {
    //     dd($attributes['time']);
    //     $days = $time->toDateTimeString();
    //     $date = $this->model()::where('time_details', $time)->get();
    //     dd($date);
    // }
    // public function searchuser($key)
    // {
    //     $user = $this->model()::where('name', 'like', '%' . $key . '%')->orwhere('email', 'like', '%' . $key . '%')->get();
    //     return $this->parserResult($user);
    // }
}
