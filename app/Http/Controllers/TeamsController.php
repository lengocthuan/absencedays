<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Repositories\Contracts\TeamRepository;
use Illuminate\Http\Response;

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
        $teams = $this->repository->all();

        return $this->success($teams, trans('messages.team.success'));
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

        return $this->success($team->presenter(), trans('messages.team.create'), ['code' => Response::HTTP_CREATED]);

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
        
        return $this->success($team, trans('messages.team.success'));
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

        return $this->success($team->presenter(), trans('messages.team.update'));
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

        return $this->success([], trans('messages.team.delete'), ['code' => Response::HTTP_NO_CONTENT, 'isShowData' => false]);
    }
}
