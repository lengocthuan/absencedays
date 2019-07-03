<?php

namespace App\Repositories\Eloquent;

use App\Models\Approver;
use App\Presenters\ApproverPresenter;
use App\Repositories\Contracts\ApproverRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ApproverRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class ApproverRepositoryEloquent extends BaseRepository implements ApproverRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Approver::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return ApproverPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
