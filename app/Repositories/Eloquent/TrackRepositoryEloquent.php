<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\TrackRepository;
use App\Presenters\TrackPresenter;
use App\Models\Track;

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
    
}
