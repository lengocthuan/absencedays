<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\TracksExport;
use App\Exports\StatisticalsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests;
use App\Http\Requests\TrackCreateRequest;
use App\Http\Requests\TrackUpdateRequest;
use App\Repositories\Contracts\TrackRepository;
use Carbon\Carbon;
use Illuminate\Http\Response;
use App\User;
use App\Models\Track;
use App\Models\Registration;

/**
 * Class TracksController.
 *
 * @package namespace App\Http\Controllers;
 */
class TracksController extends Controller
{
    /**
     * @var TrackRepository
     */
    protected $repository;

    /**
     * TracksController constructor.
     *
     * @param TrackRepository $repository
     */
    public function __construct(TrackRepository $repository)
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
        $yearNow = Carbon::now()->format('Y');
        $tracks = $this->repository->findwhere(['year' => $yearNow]);

        return $this->success($tracks, trans('messages.track.success'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TrackCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TrackCreateRequest $request)
    {
        $track = $this->repository->skipPresenter()->create($request->all());
        if($track){
            return $this->success($track->presenter(), trans('messages.track.create'), ['code' => Response::HTTP_CREATED]);
        }
        return $this->error(trans('messages.track.errorStore'), trans('messages.track.duplicate'), Response::HTTP_BAD_REQUEST);
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
        $track = $this->repository->find($id);

        return $this->success($track, trans('messages.track.success'));
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

        return $this->success([], trans('messages.track.delete'), ['code' => Response::HTTP_NO_CONTENT, 'isShowData' => false]);
    }

    public function getStatistical(TrackCreateRequest $request)
    {
        $general =['data' => $this->repository->statistical($request->all())];
        if($general['data']) {
            return $this->success($general, trans('messages.track.statistical'));
        }
        return $this->error(trans('messages.track.error'), trans('messages.track.notExist'), Response::HTTP_BAD_REQUEST);
    }

    public function export(TrackCreateRequest $request) 
    {
        $general = $this->repository->statistical($request->all());

        return Excel::download(new TracksExport($general), "Thống kê chi tiết theo đợt $request->from-$request->to$request->year$request->month.xlsx");
    }

    public function exportStatistical()
    {
        $now = Carbon::now()->toDateString();

        return Excel::download(new StatisticalsExport, "Thống kê tổng quan theo năm-ngày xuất bản-$now.xlsx");
    }

    public function updateFromUser(Request $request)
    {
        $result = $this->repository->fromUser($request->all());
        $data = $this->repository->all();
        if($result['data'] != true) {
            return $this->success($data, trans('messages.track.success'));
        }

        return $this->success($result, trans('messages.track.success'));
    }

}
