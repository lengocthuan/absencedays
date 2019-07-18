<?php

namespace App\Repositories\Eloquent;

use App\Models\Registration;
use App\Models\TimeAbsence;
use App\Models\Track;
use App\Presenters\TrackPresenter;
use App\Repositories\Contracts\TrackRepository;
use App\User;
use Carbon\Carbon;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Services\TrackService;

/**
 * Class TrackRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class TrackRepositoryEloquent extends BaseRepository implements TrackRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Track::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return TrackPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function statistical(array $attributes)
    {
        //Statistics for user absence according to different type:
        //1. From day to day
        //2. For month of year
        //3. For year

        $user = User::select()->get();
        $registration = Registration::select('id', 'user_id', 'status')->get();

        if (isset($attributes['from']) && isset($attributes['to'])) {
            $from = $attributes['from'];
            $to = $attributes['to'];
            $result = TimeAbsence::where('time_details', '>=', $from)->where('time_details', '<=', $to)->select('registration_id', 'time_details', 'at_time', 'absence_days')->get();
        } elseif (isset($attributes['month'])) {
            $time = $attributes['month'];
            $cut = explode('-', $time);
            $month = $cut[1];
            $year = $cut[0];
            $result = TimeAbsence::whereMonth('time_details', $month)->whereYear('time_details', $year)->select('registration_id', 'time_details', 'at_time', 'absence_days')->get();
        } else {
            $time = $attributes['year'];
            $result = TimeAbsence::whereYear('time_details', $time)->select('registration_id', 'time_details', 'at_time', 'absence_days')->get();
        }

        $general = array();
        $preSum = array(); //same pre-Order
        foreach ($user as $value) {
            for ($i = 0; $i < count($registration); $i++) {
                for ($j = 0; $j < count($result); $j++) {
                    if ($value->id == $registration[$i]->user_id) {
                        if ($result[$j]->registration_id == $registration[$i]->id) {
                            $general[] = ['id' => $value->id, 'name' => $value->name, 'email' => $value->email, 'team' => $value->getTeam->name, 'position' => $value->getPosition->name, 'time_details' => Carbon::parse($result[$j]->time_details)->format('d-m-Y'), 'at_time' => $result[$j]->at_time, 'absence_days' => $result[$j]->absence_days];
                            $preSum[] = $value->id . '-' . $value->name . '-' . $value->email . '-' . $value->getTeam->name . '-' . $value->getPosition->name;
                        }
                    }

                }
            }
        }
        $totalDayOff = 0;
        $newInitArray = array(); //array is final result;
        $uniquePreSum = array_unique($preSum);
        $arrayNull = array();
        foreach ($uniquePreSum as $value) {
            $cut = explode('-', $value);
            $arrayNull[] = $cut;
        }

        for ($i = 0; $i < count($arrayNull); $i++) {
            $newInitArray[] = ['id' => $arrayNull[$i][0], 'name' => $arrayNull[$i][1], 'email' => $arrayNull[$i][2], 'team' => $arrayNull[$i][3], 'position' => $arrayNull[$i][4], 'time_details' => null, 'at_time' => null, 'absence_days' => null];
        }

        $mergeTime = array();

        for ($i = 0; $i < count($newInitArray); $i++) {
            foreach ($general as $value) {
                if ($value['id'] == $newInitArray[$i]['id']) {
                    $mergeTime[] = $value['time_details'];
                    $newInitArray[$i]['time_details'] = $mergeTime;
                    $totalDayOff += $value['absence_days'];
                    $mergeAtTime[] = $value['at_time'];
                    $newInitArray[$i]['at_time'] = $mergeAtTime;
                    $newInitArray[$i]['absence_days'] = $totalDayOff;
                }
            }
            unset($mergeTime);
            unset($mergeAtTime);
        }
        return $newInitArray;
    }

    public function create(array $attributes)
    {
        $currentUsers = $this->model()::where('user_id', $attributes['user_id'])->select('id', 'user_id', 'year')->get();
        foreach ($currentUsers as $value) {
            if ($value->year == $attributes['year']) {
                return false;
            }
        }
        return parent::create($attributes);
    }

    public function fromUser()
    {
        $newUser = Track::select('user_id', 'year')->get();

        for ($i = 0; $i < count($newUser); $i++) {
            $newArray[] = $newUser[$i]->user_id . ' ' . $newUser[$i]->year;
        }
        $oldArray = $newArray;

        $now = Carbon::now()->format('Y');
        $user = User::select('id')->get();
        foreach ($user as $key => $value) {
            $newArray[] = $value->id . ' ' . $now;
        }

        $result = array_diff($newArray, $oldArray);

        if ($result != null) {
            foreach ($result as $value) {
                $arrayCut[] = explode(' ', $value);
            }

            
            for ($i = 0; $i < count($arrayCut); $i++) {
                $addTime = TrackService::calculatorYear($arrayCut[$i][0]);
                $arrayNull[] = ['year' => $arrayCut[$i][1], 'user_id' => $arrayCut[$i][0], 'annual_leave_total' => $addTime, 'annual_leave_unused' => null, 'January' => 0.0, 'February' => 0.0, 'March' => 0.0, 'April' => 0.0, 'May' => 0.0, 'June' => 0.0, 'July' => 0.0, 'August' => 0.0, 'September' => 0.0, 'October' => 0, 'November' => 0.0, 'December' => 0.0, 'sick_leave' => 0.0, 'marriage_leave' => 0.0, 'maternity_leave' => 0.0, 'bereavement_leave' => 0.0, 'unpaid_leave' => 0.0];
            }
            for ($i = 0; $i < count($arrayNull); $i++) {
                Track::create($arrayNull[$i]);
            }

            return true;
        }

        return false;
    }

}
