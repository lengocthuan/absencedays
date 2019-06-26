<?php

namespace App\Repositories\Eloquent;

use App\Models\Registration;
use App\Models\TimeAbsence;
use App\Presenters\RegistrationPresenter;
use App\Repositories\Contracts\RegistrationRepository;
use App\Services\TimeAbsenceService;
use Carbon\Carbon;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Auth;

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

    public function search(array $attributes) {
        // dd($attributes['time']);
        $id = Auth::user()->id;
        $search = $this->model()::where('user_id', $id)->select('id')->get();
        $time = TimeAbsenceService::search($search, $attributes);
        $result = $this->model()::whereIn('id', $time)->get();
        return $this->parserResult($result);
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
        // $timestart = new Carbon($attributes['time_start']);
        // $timeend = new Carbon($attributes['time_end']);
        // $check = Registration::where('user_id', $attributes['user_id'])->first();

        // $checkTime = TimeAbsence::where('registration_id', $attributes['id'])
        // dd($check->user_id);

        if ($attributes['type'] == 'From day to day') {
            $attributes['status'] = 3;
            if ($attributes['status'] == 3) {
                $attributes['requested_date'] = Carbon::now()->toDateString();
            }
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

    // public function getTotal($id)
    // {
    //     $total = TimeAbsence::where('registration_id', $id)->select('absence_days')->get();
    //     $sum = 0;
    //     foreach ($total as $value) {
    //         $sum += $value->absence_days;
    //     }
    //     return $sum;
    // }
    // public function getTotalTime($id) {
    //     $total = TimeAbsence::where('registration_id', $id)->select('absence_days')->get();
    //     $sum = 0;
    //     foreach ($total as $value) {
    //         $sum += $value->absence_days;
    //     }
    //     return $sum;
    // }
}
