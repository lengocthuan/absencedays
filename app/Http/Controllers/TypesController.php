<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\TypeCreateRequest;
use App\Http\Requests\TypeUpdateRequest;
use App\Repositories\Contracts\TypeRepository;
use Illuminate\Http\Response;

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
        $types = $this->repository->all();

        return $this->success($types, trans('messages.type.success'));
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

        return $this->success($type->presenter(), trans('messages.type.create'), ['code' => Response::HTTP_CREATED]);
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

        return $this->success($type, trans('messages.type.success'));
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

        return $this->success($type->presenter(), trans('messages.type.update'));
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

        return $this->success([], trans('messages.type.delete'), ['code' => Response::HTTP_NO_CONTENT, 'isShowData' => false]);
    }
}
