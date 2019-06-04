<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\TypeCreateRequest;
use App\Http\Requests\TypeUpdateRequest;
use App\Repositories\Contracts\TypeRepository;

/**
 * Class TypesController.
 *
 * @package namespace App\Http\Controllers;
 */
class TypesController extends Controller
{
    /**
     * @var TypeRepository
     */
    protected $repository;

    /**
     * TypesController constructor.
     *
     * @param TypeRepository $repository
     */
    public function __construct(TypeRepository $repository)
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

        $types = $this->repository->paginate($limit, $columns = ['*']);

        return response()->json($types);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TypeCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TypeCreateRequest $request)
    {
        $type = $this->repository->skipPresenter()->create($request->all());

        return response()->json($type->presenter(), 201);
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
        $type = $this->repository->find($id);
        
        return response()->json($type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TypeUpdateRequest $request
     * @param  string $id
     *
     * @return Response
     */
    public function update(TypeUpdateRequest $request, $id)
    {
        $type = $this->repository->skipPresenter()->update($request->all(), $id);

        return response()->json($type->presenter(), 200);
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
