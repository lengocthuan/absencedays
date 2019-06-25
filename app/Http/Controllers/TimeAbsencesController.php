<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeAbsenceCreateRequest;
use App\Http\Requests\TimeAbsenceUpdateRequest;
use App\Repositories\Contracts\TimeAbsenceRepository;
use Illuminate\Http\Request;
use App\Models\TimeAbsence;
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
        $limit = request()->get('limit', null);

        $includes = request()->get('include', '');

        if ($includes) {
            $this->repository->with(explode(',', $includes));
        }

        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));

        $timeAbsences = $this->repository->paginate($limit, $columns = ['*']);

        return response()->json($timeAbsences);
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
        if ($timeAbsence == 'Over') {
            $error = 'You must not register for a continuous stay for more than 15 consecutive days.';
            return response()->json($error, 404);
        } elseif ($timeAbsence == 'Invalidate') {
            $error = 'Registration time is invalid. Please, try again.';
            return response()->json($error, 404);
        }
        elseif ($timeAbsence == 'InvalidateType') {
            $error = 'Selection of inappropriate registration. Please try again.';
            return response()->json($error, 404);
        }
        else {
            return response()->json($timeAbsence, 201);
        }
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

        return response()->json($timeAbsence);
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

        return response()->json($timeAbsence->presenter(), 200);
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

    public function statistic($id)
    {
        /*
        get Total absence days for a user in a registration.
        */
        $total = TimeAbsence::where('registration_id', $id)->select('absence_days')->get();
        $sum = 0;
        foreach ($total as $value) {
            $sum += $value->absence_days;
        }
        return $sum;
    }
}
