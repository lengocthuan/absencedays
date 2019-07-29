<?php

namespace App\Repositories\Eloquent;

use App\Models\Registration;
use App\Models\TimeAbsence;
use App\Models\Track;
use App\Presenters\TrackPresenter;
use App\Repositories\Contracts\TrackRepository;
use App\Services\TrackService;
use App\User;
use Carbon\Carbon;
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
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        $user = User::select()->get();
        $registration = Registration::select('id', 'user_id', 'status', 'type_id', 'note')->get();
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
        } elseif (isset($attributes['week'])) {
            $week = Carbon::parse($attributes['week']);
            $start = $week->startOfWeek()->toDateString();
            $end = $week->endOfWeek()->toDateString();

            $result = TimeAbsence::whereBetween('time_details', [$start, $end])->select('registration_id', 'time_details', 'at_time', 'absence_days')->get();
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
                            $general[] = ['id' => $value->id, 'name' => $value->name, 'email' => $value->email, 'team' => $value->getTeam->name, 'position' => $value->getPosition->name, 'time_details' => Carbon::parse($result[$j]->time_details)->format('d-m-Y'), 'at_time' => $result[$j]->at_time, 'absence_days' => $result[$j]->absence_days, 'type_off' => $registration[$i]->getType->name, 'note' => $registration[$i]->note];
                            $preSum[] = $value->id . '-' . $value->name . '-' . $value->email . '-' . $value->getTeam->name . '-' . $value->getPosition->name;
                        }
                    }

                }
            }
        }
        if (count($general)) {
            $totalDayOff = 0;
            $newInitArray = array(); //array is final result;
            $uniquePreSum = array_unique($preSum);
            $arrayNull = array();

            foreach ($uniquePreSum as $value) {
                $cut = explode('-', $value);
                $arrayNull[] = $cut;
            }

            for ($i = 0; $i < count($arrayNull); $i++) {
                $mergeId[] = (integer) $arrayNull[$i][0];
            }

            for ($i = 0; $i < count($user); $i++) {
                $mergeIdUser[] = $user[$i]->id;
            }

            $uniqueId = array_diff($mergeIdUser, $mergeId);
            $addUserNull = User::whereIn('id', $uniqueId)->get();

            for ($i = 0; $i < count($addUserNull); $i++) {
                $resultFinal[] = ['id' => $addUserNull[$i]->id, 'name' => $addUserNull[$i]->name, 'email' => $addUserNull[$i]->email, 'team' => $addUserNull[$i]->getTeam->name, 'position' => $addUserNull[$i]->getPosition->name, 'time_details' => null, 'at_time' => null, 'absence_days' => null, 'type_off' => null, 'note' => null];
            }

            for ($i = 0; $i < count($arrayNull); $i++) {
                $newInitArray[] = ['id' => $arrayNull[$i][0], 'name' => $arrayNull[$i][1], 'email' => $arrayNull[$i][2], 'team' => $arrayNull[$i][3], 'position' => $arrayNull[$i][4], 'time_details' => null, 'at_time' => null, 'absence_days' => null, 'type_off' => null, 'note' => null];
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
                        $newInitArray[$i]['type_off'] = $value['type_off'];
                        $newInitArray[$i]['note'] = $value['note'];
                    }

                }
                $totalDayOff = 0;
                unset($mergeTime);
                unset($mergeAtTime);
            }

            if (isset($attributes['absences'])) {
                switch ($attributes['absences']) {
                    case 0:
                        return $resultFinal;
                        break;

                    case 1:
                        return $newInitArray;
                        break;

                    default:
                        break;
                }
            }
            $result = array_merge($resultFinal, $newInitArray);

            if (isset($attributes['year'])) {
                for ($i = 0; $i < count($result); $i++) {
                    $result[$i]['year'] = $attributes['year'];
                }
            }

            return $result;
        }
        return false;
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
        $now = Carbon::now()->format('Y');
        $user = User::select('id')->get();
        $newUser = Track::select('user_id')->get();

        for ($i = 0; $i < count($user); $i++) {
            $userId[] = $user[$i]->id;
        }
        $merge = $userId;

        if (!$newUser->isEmpty()) {
            for ($i = 0; $i < count($newUser); $i++) {
                $trackUserId[] = $newUser[$i]->user_id;
            }
            if (array_diff($userId, $trackUserId) == null) {
                return false;
            }
            $merge = array_values(array_diff($userId, $trackUserId));
        }

        for ($i = 0; $i < count($merge); $i++) {
            $addTime = TrackService::calculatorYear($merge[$i]);
            $arrayNull[] = ['year' => $now, 'user_id' => $merge[$i], 'annual_leave_total' => $addTime, 'annual_leave_unused' => $addTime];
        }

        for ($i = 0; $i < count($arrayNull); $i++) {
            $create = parent::create($arrayNull[$i]);
        }

        return true;

    }

    public function filterForRequest($attributes)
    {
        $now = Carbon::now()->format('Y');
        $currentYearNonNull = Track::where('year', $now)->where('annual_leave_unused', '!=', null)->get();
        $currentYearNull = Track::where('year', $now)->where('annual_leave_unused', null)->get();
        $newUser = Track::select('user_id')->get();
        if (!$newUser->isEmpty()) {
            if (isset($attributes['absences'])) {
                switch ($attributes['absences']) {
                    case 0:
                        return $this->parserResult($currentYearNull);
                        break;

                    case 1:
                        return $this->parserResult($currentYearNonNull);
                        break;

                    default:
                        break;
                }
            }
        }
        return false;
    }

}
