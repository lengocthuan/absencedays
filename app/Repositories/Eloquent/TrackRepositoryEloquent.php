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

        $user = User::select('id')->orderBy('id')->get();
        // dd($user[0]);
        // $user = User::select(['id', 'name', 'email'])->get();
        $registration = Registration::select('id', 'user_id')->get();
        $array = array();

        foreach ($user as $value) {
            for ($i = 0; $i < count($registration); $i++) {
                if ($value->id == $registration[$i]->user_id) {
                    $array[] = $registration[$i]->id . "-" . $registration[$i]->user_id;
                }

            }
        }
        // dd($array);

        $detail = array();
        $timeDetails = TimeAbsence::select('registration_id', 'time_details', 'at_time')->get();
        for ($i = 0; $i < count($registration); $i++) {
            foreach ($timeDetails as $value) {
                if ($registration[$i]->user_id == $value->registration_id) {
                    $regis_id = $value->registration_id;
                    $time = $value->time_details;
                    $at = $value->at_time;
                    $detail[] = $registration[$i]->user_id . " " . $regis_id . " " . $time . " " . $at;
                }

            }
        }

        dd($detail);
        dd($registration);

    }
}
