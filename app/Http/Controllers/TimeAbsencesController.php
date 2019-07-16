<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeAbsenceCreateRequest;
use App\Http\Requests\TimeAbsenceUpdateRequest;
use App\Models\TimeAbsence;
use App\Repositories\Contracts\TimeAbsenceRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class TimeAbsencesController.
 *
 * @package namespace App\Http\Controllers;
 */
class TimeAbsencesController extends Controller
{
    /**
     * @var TimeAbsenceRepository
     */
    protected $repository;

    /**
     * TimeAbsencesController constructor.
     *
     * @param TimeAbsenceRepository $repository
     */
    public function __construct(TimeAbsenceRepository $repository)
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
        $timeAbsences = $this->repository->all();

        return $this->success($timeAbsences, trans('messages.timeabsence.success'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TimeAbsenceCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TimeAbsenceCreateRequest $request)
    {
        // $timeAbsence = $this->repository->skipPresenter()->create($request->all());

        // return response()->json($timeAbsence, 201);

        $timeAbsence = $this->repository->skipPresenter()->create($request->all());

        return $this->success($timeAbsence, trans('messages.timeabsence.create'), ['code' => Response::HTTP_CREATED]);
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
        $timeAbsence = $this->repository->find($id);

        return $this->success($timeAbsence, trans('messages.timeabsence.success'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TimeAbsenceUpdateRequest $request
     * @param  string $id
     *
     * @return Response
     */
    public function update(TimeAbsenceUpdateRequest $request, $id)
    {
        $timeAbsence = $this->repository->skipPresenter()->update($request->all(), $id);

        return $this->success($timeAbsence, trans('messages.timeabsence.update'));
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

        return $this->success([], trans('messages.timeabsence.delete'), ['code' => Response::HTTP_NO_CONTENT, 'isShowData' => false]);
    }

}
