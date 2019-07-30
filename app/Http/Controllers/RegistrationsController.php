<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationCreateRequest;
use App\Http\Requests\RegistrationUpdateRequest;
use App\Http\Requests\RegistrationUpdateStatusRequest;
use App\Models\Registration;
use App\Repositories\Contracts\RegistrationRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
        $registrations = $this->repository->all();

        return $this->success($registrations, trans('messages.registration.success'));
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
        $registration = $this->repository->create($request->all());

        if ($registration == Registration::DUPLICATE_TIME) {
            return $this->error(trans('messages.registration.error'), trans('messages.registration.duplicateTime'), Response::HTTP_BAD_REQUEST);
        }
        if ($registration) {
            return $this->success($registration, trans('messages.registration.createSuccess'), ['code' => Response::HTTP_CREATED]);
        }
        return $this->error(trans('messages.registration.error'), trans('messages.registration.duplicate'), Response::HTTP_BAD_REQUEST);
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

        return $this->success($registration, trans('messages.registration.success'));
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
        $registration = $this->repository->update($request->all(), $id);
        if ($registration == Registration::DUPLICATE_TIME) {
            return $this->error(trans('messages.registration.error'), trans('messages.registration.duplicateTime'), Response::HTTP_BAD_REQUEST);
        }
        if ($registration) {
            return $this->success($registration, trans('messages.registration.updateSuccess'), ['code' => Response::HTTP_CREATED]);
        }
        return $this->error(trans('messages.registration.invalidRegistration'), trans('messages.registration.statusNotSuitable'), Response::HTTP_BAD_REQUEST);

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

        return $this->success([], trans('messages.registration.delete'), ['code' => Response::HTTP_NO_CONTENT, 'isShowData' => false]);
    }

    /*
    Get information Time absences of users
     */
    public function getStatus(Request $request)
    {
        $id = Auth::id();
        switch ($request->status) {
            case '3':
                $status = 3;
                break;
            case '1':
                $status = 1;
                break;
            case '2':
                $status = 2;
                break;

            default:
                $status = 0;
                break;
        }
        $user = $this->repository->findwhere(['user_id' => $id, 'status' => $status]);
        return $this->success($user, trans('messages.registration.success'));
    }

    public function getRegistrationPending()
    {
        $email = Auth::user()->email;
        $pending = $this->repository->getPending($email);
        return $this->success($pending, trans('messages.registration.search'));
    }

    public function searchRegistrationPending(Request $request)
    {
        $pending = $this->repository->searchRegistrationPending($request->all());
        return $this->success($pending, trans('messages.registration.search'));
    }

    public function search(Request $request)
    {
        $days = $this->repository->search($request->all());

        return $this->success($days, trans('messages.registration.search'));
    }

    public function updateStatus1($id, RegistrationUpdateStatusRequest $request)
    {
        $info = Auth::id();
        $user1 = Registration::where('id', $id)->select('status')->get();
        if ($user1[0]->status == 2) {
            return $this->error(trans('messages.registration.statusDisapproved'), trans('messages.registration.statusDisapproved'), Response::HTTP_BAD_REQUEST);
        } else {
            $checkBeforeApply = $this->repository->checkBeforeApply($id);
            if ($checkBeforeApply) {
                $approvedBy = User::where('id', $info)->first();
                $userRegistration = Registration::where('id', $id)->update(['status' => 1, 'approved_by' => $approvedBy->name]);
                $date = Carbon::now();
                $aprrovedDate = Registration::where('id', $id)->update(['approved_date' => $date]);
                $attributes = Registration::where('id', $id)->get();

                TrackService::update($id, $attributes);

                $message = $this->repository->getMessage($id, $request->all());
                $mailUpdate = $this->repository->updateMail($id, $userRegistration);
                $information = $this->repository->findwhere(['id' => $id]);

                return $this->success($information, trans('messages.registration.updateStatus'));
            }
            return $this->error(trans('messages.registration.badRequest'), trans('messages.registration.trackInvalid'), Response::HTTP_BAD_REQUEST);

        }

    }

    public function updateStatus2($id, RegistrationUpdateStatusRequest $request)
    {
        $user1 = Registration::where('id', $id)->select('status')->get();
        if ($user1[0]->status == 1) {
            return $this->error(trans('messages.registration.statusApproved'), trans('messages.registration.statusApproved'), Response::HTTP_BAD_REQUEST);
        } elseif ($user1[0]->status == 3) {
            $user = Registration::where('id', $id)->update(['status' => 2]);
            $user = Registration::where('id', $id)->select('status')->get();
            $user = $user[0]->status;
            $date = Carbon::now();
            $aprrovedDate = Registration::where('id', $id)->update(['approved_date' => $date]);
            $message = $this->repository->getMessage($id, $request->all());
            $mailUpdate = $this->repository->updateMail($id, $user);
            $information = $this->repository->findwhere(['id' => $id]);
            return $this->success($information, trans('messages.registration.updateStatus'));
        } else {
            return $this->error(trans('messages.registration.statusInvalid'), trans('messages.registration.statusInvalid'), Response::HTTP_BAD_REQUEST);
        }
    }

    public function updateMessage($id, RegistrationUpdateStatusRequest $request)
    {
        $user1 = Registration::where('id', $id)->select('status')->get();
        if ($user1[0]->status == 1) {
            return $this->error(trans('messages.registration.statusApproved'), trans('messages.registration.statusApproved'), Response::HTTP_BAD_REQUEST);
        } elseif ($user1[0]->status == 2) {
            return $this->error(trans('messages.registration.statusDisapproved'), trans('messages.registration.statusDisapproved'), Response::HTTP_BAD_REQUEST);
        } else {
            $user = 3;
            $message = $this->repository->getMessage($id, $request->all());
            $mailUpdate = $this->repository->updateMail($id, $user);
            $information = $this->repository->findwhere(['id' => $id]);
            return $this->success($information, trans('messages.registration.updateStatus'));
        }
    }

    public function test()
    {
        //use test;
        $approver = Registration::select('approver_id')->get();
        $app = explode(',', $approver[0]->approver_id);
        $arrayTest = array();
        foreach ($app as $value) {
            $info = User::where('id', $value)->get();
            $arrayTest[] = $info;
        }
        dd($arrayTest);
    }

    public function registrationApprovedBy()
    {
        $registrations = $this->repository->findwhere(['status' => 1]);

        return $this->success($registrations, trans('messages.registration.success'));
    }
}
