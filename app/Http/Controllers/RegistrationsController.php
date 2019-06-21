<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\RegistrationCreateRequest;
use App\Http\Requests\RegistrationUpdateRequest;
use App\Repositories\Contracts\RegistrationRepository;
use Carbon\Carbon;
use App\Models\Registration;
use illuminate\database\eloquent\collection;

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

    /*
     create a new registration
    */
    // public function create(RegistrationCreateRequest $request)
    // {
    //     $registration = $this->repository->skipPresenter()->create($request->all());

    //     return $this->presenterPostJson($registration);
    // }

     public function test()
     {
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
