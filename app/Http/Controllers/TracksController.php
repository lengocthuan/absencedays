<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\TrackCreateRequest;
use App\Http\Requests\TrackUpdateRequest;
use App\Repositories\Contracts\TrackRepository;

/**
 * Class TracksController.
 *
 * @package namespace App\Http\Controllers;
 */
class TracksController extends Controller
{
    /**
     * @var TrackRepository
     */
    protected $repository;

    /**
     * TracksController constructor.
     *
     * @param TrackRepository $repository
     */
    public function __construct(TrackRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = request()->get('limit', null);
        
        $includes = request()->get('include', '');

        if ($includes) {
            $this->repository->with(explode(',', $includes));
        }

        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));

        $tracks = $this->repository->paginate($limit, $columns = ['*']);

        return response()->json($tracks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TrackCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TrackCreateRequest $request)
    {
        $track = $this->repository->skipPresenter()->create($request->all());

        return response()->json($track->presenter(), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $track = $this->repository->find($id);
        
        return response()->json($track);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TrackUpdateRequest $request
     * @param  string $id
     *
     * @return Response
     */
    public function update(TrackUpdateRequest $request, $id)
    {
        $track = $this->repository->skipPresenter()->update($request->all(), $id);

        return response()->json($track->presenter(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return response()->json(null, 204);
    }

    public function getStatistical(TrackCreateRequest $request)
    {
        $general = $this->repository->statistical($request->all());
        return response()->json($general);
    }
}
