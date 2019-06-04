<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\RegistrationCreateRequest;
use App\Http\Requests\RegistrationUpdateRequest;
use App\Repositories\Contracts\RegistrationRepository;

/**
 * Class RegistrationsController.
 *
 * @package namespace App\Http\Controllers;
 */
class RegistrationsController extends Controller
{
    /**
     * @var RegistrationRepository
     */
    protected $repository;

    /**
     * RegistrationsController constructor.
     *
     * @param RegistrationRepository $repository
     */
    public function __construct(RegistrationRepository $repository)
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

        $registrations = $this->repository->paginate($limit, $columns = ['*']);

        return response()->json($registrations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RegistrationCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RegistrationCreateRequest $request)
    {
        $registration = $this->repository->skipPresenter()->create($request->all());

        return response()->json($registration->presenter(), 201);
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
        $registration = $this->repository->find($id);
        
        return response()->json($registration);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RegistrationUpdateRequest $request
     * @param  string $id
     *
     * @return Response
     */
    public function update(RegistrationUpdateRequest $request, $id)
    {
        $registration = $this->repository->skipPresenter()->update($request->all(), $id);

        return response()->json($registration->presenter(), 200);
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
