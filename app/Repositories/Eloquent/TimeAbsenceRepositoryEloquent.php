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
    
}
