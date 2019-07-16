<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\PositionCreateRequest;
use App\Http\Requests\PositionUpdateRequest;
use App\Repositories\Contracts\PositionRepository;
use Illuminate\Http\Response;

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
        $positions = $this->repository->all();

        return $this->success($positions, trans('messages.position.success'));
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

        return $this->success($position->presenter(), trans('messages.position.create'), ['code' => Response::HTTP_CREATED]);
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

        return $this->success($position, trans('messages.position.success'));
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

        return $this->success($position->presenter(), trans('messages.position.update'));
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

        return $this->success([], trans('messages.position.delete'), ['code' => Response::HTTP_NO_CONTENT, 'isShowData' => false]);
    }
}
