<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\RegistrationCreateRequest;
use App\Http\Requests\RegistrationUpdateRequest;
use App\Repositories\Contracts\RegistrationRepository;
use Carbon\Carbon;
use App\Models\Registration;
use App\Models\TimeAbsence;
use App\User;
use illuminate\database\eloquent\collection;
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
        
        $registration = $this->repository->create($request->all());
        if($registration == 'error')
        {
            $error = 'You cant registration over 17 days.';
            return response()->json($error, 404);
        } elseif($registration == 'unallow')
        {
            $error = 'You are not allowed to set a date that coincides with the previously registered date.';
            return response()->json($error, 404);
        } elseif($registration == 'time_invalid')
        {
            $error = 'Registration time is invalid';
            return response()->json($error, 404);
        } elseif($registration == 'time_invalid_1')
        {
            $error = 'Total inappropriate absence time, other time options or contact administrator.';
            return response()->json($error, 404);
        } else
        {
            return response()->json($registration, 201);
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
        $registration = $this->repository->update($request->all(), $id);
        if($registration == 'unsuitable') {
            $error = 'Your registration table has an inappropriate state in this case.';
            return response()->json($error, 404);
        }
        return response()->json($registration, 200);
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

    /*
    Get information Time absences of users
    */
    public function getProfile($id)
    {
        $user = $this->repository->findwhere(['user_id' => $id, 'status' => '3']);
        // $user = $this->repository->findwhere(['user_id' => $id, 'status' => '3']);
        return response()->json($user, 200);
    }

    public function getApproved($id)
    {
        $user = $this->repository->findwhere(['user_id' => $id, 'status' => '1']);
        // $user = $this->repository->findwhere(['user_id' => $id, 'status' => '3']);
        return response()->json($user, 200);
    }

    public function search(Request $request )
    {

        $days = $this->repository->search($request->all());
        return response()->json($days);
    }

    public function getRegisPending()
    {
        $email = Auth::user()->email;
        $pending = $this->repository->getPending($email);
        return response()->json($pending, 200);
    }

    public function updateStatusRegis($id)
    {
        $user = Registration::where('id', $id)->update(['status' => 1]);
        $date = Carbon::now();
        $aprroved_date = Registration::where('id', $id)->update(['approved_date' => $date]);
        $information = $this->repository->findwhere(['id' => $id]);
        return response()->json($information, 200);
    }
    // public function searchuser($key)
    // {
    //     $user = $this->repository->searchuser($key);
    //     return response()->json($user);
    // }

     public function test()
     {
        $approver = Registration::select('approver_id')->get();
        $app = explode(',', $approver[0]->approver_id);
        // dd($approver[0]->approver_id);
        // dd($app[0]);
        $arr = array();
        foreach ($app as $value) {
            $info = User::where('id', $value)->get();
            $arr[] =$info;
        }
        dd($arr);
        // for($i = 0; $i < 3; $i++){
        //     $array = Array($i);
        // }
        // echo $array;
        // echo [1,2].length;
        // $y = Carbon::now()->format('Y');
        // // 2019-06-14
        // echo $y;
        // echo "==========";
        // $m = Carbon::now();
        // echo $m;
        //  echo "==========";
        // // $d = Carbon::now()->format('Y');
        // echo $y;
        // echo "==========";
        // $time1 = "31-12" .$y;
        // echo $time1;
        // echo "==========";
        // $time2 = Carbon::parse($time1);
        // // $time2 = strtotime($time1);
        // // $newformat = date('Y-m-d',$time2);
        // echo $time2;
        // // echo "==========";

        // // $current = Carbon::now();
        // $sum = $time2 - $m;
        // echo $sum;

        // $newformat = date('y',$time1)+1;

        // echo $newformat;
        // echo"=============";
        // // $time1 = "2019-06-11";
        // if($time === $newformat) echo "giong nhau"; else echo "khong giong nhau";
        // var_dump($time);
        // if()

        //
        //
        //
        // $registration = Registration::where('id',10)->first();
        // // $registration1 = Registration::where('id',10)->get(['time_off_ending']);
        // // if($registration === $registration1) echo "="; else echo "!=";
      
        // $time = explode(' ', $registration->time_off_beginning);
        // echo $time[0];

        // $time1 = explode(' ', $registration->time_off_ending);
        // echo $time1[0];

        // if($time1[0] == $time[0]) echo '='; else echo"!=";
        // //echo $registration;
        //
        //
        // $time = new Carbon('2019-06-12');
        // $day = $time->toDateString();
        // echo $time->addDay(2);
        // echo "=====";
        // echo $day;
     }
}
