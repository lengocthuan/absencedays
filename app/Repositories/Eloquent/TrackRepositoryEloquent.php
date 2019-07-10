<?php

namespace App\Repositories\Eloquent;

use App\Models\Registration;
use App\Models\TimeAbsence;
use App\Models\Track;
use App\Presenters\TrackPresenter;
use App\Repositories\Contracts\TrackRepository;
use App\User;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

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
        // dd($user);

        $registration = Registration::select('id', 'user_id')->get();

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
        $preSum = array();
        foreach ($user as $value) {
            for ($i = 0; $i < count($registration); $i++) {
                for ($j = 0; $j < count($result); $j++) {
                    if ($value->id == $registration[$i]->user_id) {
                        if ($result[$j]->registration_id == $registration[$i]->id) {
                            $general[] = ['id' => $value->id, 'name' => $value->name, 'email' => $value->email, 'time_details' => $result[$j]->time_details, 'at_time' => $result[$j]->at_time, 'absence_days' => $result[$j]->absence_days];
                            $preSum[] = $value->id . '-' . $value->name . '-' . $value->email;
                            $preSum1[] = $value->id;
                        }
                    }

                }
            }
        }
        $totalDayOff = 0;
        $newInitArray = array();
        $countLoop = array_count_values($preSum1);
        $uniquePreSum = array_unique($preSum);
        $arr = array();
        foreach ($uniquePreSum as $value) {
            $cut = explode('-', $value);
            $arr[] = $cut;
        }

        for ($i = 0; $i < count($arr); $i++) {
            $newInitArray[] = ['id' => $arr[$i][0], 'name' => $arr[$i][1], 'email' => $arr[$i][2], 'time_details' => null, 'at_time' => null, 'absence_days' => null];
        }

        $mergeTime = array();

        for ($i = 0; $i < count($newInitArray); $i++) {
            foreach ($general as $value) {
                if ($value['id'] == $newInitArray[$i]['id']) {
                    $mergeTime[] = $value['time_details'];
                    $newInitArray[$i]['time_details'] = $mergeTime;
                    $str = implode(', ', $newInitArray[$i]['time_details']);
                    $newInitArray[$i]['time_details'] = $str;
                    $totalDayOff += $value['absence_days'];
                    $mergeAtTime[] = $value['at_time'];
                    $newInitArray[$i]['at_time'] = $mergeAtTime;
                    $str1 = implode(', ', $newInitArray[$i]['at_time']);
                    $newInitArray[$i]['at_time'] = $str1;
                    $newInitArray[$i]['absence_days'] = $totalDayOff;
                }

            }
            unset($mergeTime);
            unset($mergeAtTime);
        }

        return $newInitArray;

    }
}
