<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproverCreateRequest;
use App\Http\Requests\ApproverUpdateRequest;
use App\Repositories\Contracts\ApproverRepository;
use Illuminate\Http\Request;

/**
 * Class ApproversController.
 *
 * @package namespace App\Http\Controllers;
 */
class ApproversController extends Controller
{
    /**
     * @var ApproverRepository
     */
    protected $repository;

    /**
     * ApproversController constructor.
     *
     * @param ApproverRepository $repository
     */
    public function __construct(ApproverRepository $repository)
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

        $approvers = $this->repository->paginate($limit, $columns = ['*']);

        return response()->json($approvers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ApproverCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ApproverCreateRequest $request)
    {
        $approver = $this->repository->skipPresenter()->create($request->all());

        return response()->json($approver->presenter(), 201);
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
        $approver = $this->repository->find($id);

        return response()->json($approver);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ApproverUpdateRequest $request
     * @param  string $id
     *
     * @return Response
     */
    public function update(ApproverUpdateRequest $request, $id)
    {
        $approver = $this->repository->skipPresenter()->update($request->all(), $id);

        return response()->json($approver->presenter(), 200);
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

    public function getMailto()
    {
        $to = $this->repository->findwhere(['type' => 0]);
        return response()->json($to, 200);
    }

    public function getMailcc()
    {
        $cc = $this->repository->findwhere(['type' => 1]);
        return response()->json($cc, 200);
    }
}
