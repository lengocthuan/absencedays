<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproverCreateRequest;
use App\Http\Requests\ApproverUpdateRequest;
use App\Repositories\Contracts\ApproverRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $approvers = $this->repository->all();

        return $this->success($approvers, trans('messages.approver.success'));
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

        return $this->success($approver->presenter(), trans('messages.approver.create'), ['code' => Response::HTTP_CREATED]);
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

        return $this->success($approver, trans('messages.approver.success'));
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

        return $this->success($approver->presenter(), trans('messages.approver.update'));
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

        return $this->success([], trans('messages.approver.delete'), ['code' => Response::HTTP_NO_CONTENT, 'isShowData' => false]);
    }

    // public function getMailto()
    // {
    //     $mailTo = $this->repository->findwhere(['type' => 0]);

    //     return $this->success($mailTo, trans('messages.approver.success'));
    // }

    // public function getMailcc()
    // {
    //     $mailCc = $this->repository->findwhere(['type' => 1]);

    //     return $this->success($mailCc, trans('messages.approver.success'));
    // }
}
