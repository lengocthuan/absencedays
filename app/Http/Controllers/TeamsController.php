<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Repositories\Contracts\TeamRepository;

/**
 * Class TeamsController.
 *
 * @package namespace App\Http\Controllers;
 */
class TeamsController extends Controller
{
    /**
     * @var TeamRepository
     */
    protected $repository;

    /**
     * TeamsController constructor.
     *
     * @param TeamRepository $repository
     */
    public function __construct(TeamRepository $repository)
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

        $teams = $this->repository->paginate($limit, $columns = ['*']);

        return response()->json($teams);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TeamCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TeamCreateRequest $request)
    {
        $team = $this->repository->skipPresenter()->create($request->all());

        return response()->json($team->presenter(), 201);
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
        $team = $this->repository->find($id);
        
        return response()->json($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TeamUpdateRequest $request
     * @param  string $id
     *
     * @return Response
     */
    public function update(TeamUpdateRequest $request, $id)
    {
        $team = $this->repository->skipPresenter()->update($request->all(), $id);

        return response()->json($team->presenter(), 200);
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
}
