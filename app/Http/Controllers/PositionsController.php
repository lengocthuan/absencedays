<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\PositionCreateRequest;
use App\Http\Requests\PositionUpdateRequest;
use App\Repositories\Contracts\PositionRepository;

/**
 * Class PositionsController.
 *
 * @package namespace App\Http\Controllers;
 */
class PositionsController extends Controller
{
    /**
     * @var PositionRepository
     */
    protected $repository;

    /**
     * PositionsController constructor.
     *
     * @param PositionRepository $repository
     */
    public function __construct(PositionRepository $repository)
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

        $positions = $this->repository->paginate($limit, $columns = ['*']);

        return response()->json($positions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PositionCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PositionCreateRequest $request)
    {
        $position = $this->repository->skipPresenter()->create($request->all());

        return response()->json($position->presenter(), 201);
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
        $position = $this->repository->find($id);
        
        return response()->json($position);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PositionUpdateRequest $request
     * @param  string $id
     *
     * @return Response
     */
    public function update(PositionUpdateRequest $request, $id)
    {
        $position = $this->repository->skipPresenter()->update($request->all(), $id);

        return response()->json($position->presenter(), 200);
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
