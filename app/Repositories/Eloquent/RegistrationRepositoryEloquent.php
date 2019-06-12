<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\RegistrationRepository;
use App\Presenters\RegistrationPresenter;
use App\Models\Registration;
use App\Models\Type;
use App\Models\User;
use DB;
use Carbon\Carbon;

/**
 * Class RegistrationRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class RegistrationRepositoryEloquent extends BaseRepository implements RegistrationRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Registration::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return RegistrationPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function create(array $attributes)
    {
        $start = new Carbon($attributes['time_off_beginning']);
        $end = new Carbon($attributes['time_off_ending']);

        if($attributes['time_off_beginning'] == $attributes['time_off_ending'])
        {
            $attributes['annual_leave'] = 1;
        }
        if($attributes['time_off_beginning'] != $attributes['time_off_ending'])
        {
            
            if( $end->month == $start->month+1)
            {
                
                if($end->day == 1)
                {
                    
                    if($end->month == 4 || $end->month == 6 || $end->month == 9 || $end->month == 11 || $end->month == 2 || $end->month == 8 || $end->month == 1)
                    {
                        
                        $attributes['annual_leave'] = 31 - $start->day +1 +1;
                        //day off 30 (30 - 31 - 1) {31 - 30 = 1 + 1 + 1}
                        //day off 29 (29 - 30 - 31 - 1) {31 - 29 = 2 + 1 + 1}
                    }
                    elseif($end->month == 3) 
                    {
                        if($end->year == 2100)
                        {
                            $attributes['annual_leave'] = 28 - $start->day +1 +1;
                            //2100 % 400 != 0;
                        }
                        else 
                        {
                            if($end->year % 4 == 0)
                            {
                                $attributes['annual_leave'] = 29 - $start->day +1 +1;
                                //YYYY % 4 == 0 => This is Leap Year
                            }
                            else $attributes['annual_leave'] = 28 - $start->day +1 +1;
                        }
                    }
                    else
                    {
                        $attributes['annual_leave'] = 30 - $start->day +1 +1;
                    }
                }
                 elseif($end->day > 1 && $end->day<= 15)
                {
                    
                    if($end->month == 4 || $end->month == 6 || $end->month == 9 || $end->month == 11 || $end->month == 2 || $end->month == 8 || $end->month == 1)
                    {
                        $attributes['annual_leave'] = 31 - $start->day + 1 + $end->day;
                        //day off 30 (30 - 31 - 1) {31 - 30 = 1 + 1 + the day ending}
                        //example : beginning day off is 30/5 and ending day off is 2/6
                        //We have total dayoff == 30 31 1 2 == 31 - 30 + 1 + 2 == 4 => That is true;
                        //day off 29 (29 - 30 - 31 - 1) {31 - 29 = 2 + 1 + 1}
                    }
                    elseif($end->month == 3) 
                    {
                        if($end->year == '2100')
                        {
                            $attributes['annual_leave'] = 28 - $start->day + 1 + $end->day;
                            //2100 % 400 != 0;
                        }
                        else 
                        {
                            if($end->year % 4 == 0)
                            {
                                $attributes['annual_leave'] = 29 - $start->day +1 + $end->day;
                                //YYYY % 4 == 0 => This is Leap Year
                            }
                            else $attributes['annual_leave'] = 28 - $start->day + 1 + $end->day;
                        }
                    }
                    else
                    {
                        $attributes['annual_leave'] = 30 - $start->day +1 + $end->day;
                    }
                 }
                else
                {
                    return 'error';
                }
            }

        }

        // if($attributes['type_id'] == 1)
        // {
        //     //fill in annual-leave

        // }
        // if($attributes['type_id'] == 2)
        // {
        //     //fill in sick leave

        // }
        // if($attributes['type_id'] == 3)
        // {
        //     //fill in marriage leave

        // }
        // if($attributes['type_id'] == 4)
        // {
        //     //fill in maternity leave

        // }
        // if($attributes['type_id'] == 5)
        // {
        //     //fill in bereavement leave

        // }
        // if($attributes['type_id'] == 6)
        // {
        //     //fill in short term unpaid leave

        // }
        // if($attributes['type_id'] == 7)
        // {
        //     //fill in long term unpaid leave

        // }

        // if($attributes['type_id'] == 1) {
        //     $types = parent::create(array_push($attributes, 'annual_leave'))
        // }
         $registration = parent::create($attributes);
         return $registration;
        // // find or create role admin
        // if (!empty($attributes['role'])) {
        //     RoleService::add($user, $attributes['role']);
        // }
    }
    
}
