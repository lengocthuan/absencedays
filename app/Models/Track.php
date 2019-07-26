<?php

namespace App\Models;

use App\Models\Registration;
use App\Models\TimeAbsence;
use App\Traits\InformationUserTrait;
use Carbon\Carbon;

/**
 * Class Track.
 *
 * @package namespace App\Models;
 */
class Track extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use InformationUserTrait;
    protected $fillable = ['user_id', 'year', 'annual_leave_total', 'annual_leave_unused', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'sick_leave', 'marriage_leave', 'maternity_leave', 'bereavement_leave', 'unpaid_leave'];

    private $user;

    public function getInfoUser()
    {
        return $this->InfoUser($this->user_id);
    }

    public function getRegistration()
    {
        $id = $this->user_id;
        $registration = Registration::where('user_id', $id)->get();
        $arrayRegistration = array();

        for ($i = 0; $i < count($registration); $i++) {
            $arrayRegistration[] = $registration[$i]->id;
        }

        $arrayTimeAbsence = array();
        $arrayResult = array();

        for ($i = 0; $i < count($arrayRegistration); $i++) {
            $time = TimeAbsence::where('registration_id', $arrayRegistration[$i])->get();
            $arrayTimeAbsence[] = $time;
        }

        foreach ($arrayTimeAbsence as $val) {
            for ($i = 0; $i < count($val); $i++) {
                $merge = ['id' => $val[$i]['id'], 'registration_id' => $val[$i]['registration_id'], 'type' => $val[$i]['type'], 'time_details' => $val[$i]['time_details'], 'at_time' => $val[$i]['at_time'], 'absence_days' => $val[$i]['absence_days'], 'current_year' => $val[$i]['current_year'], 'general_information' => $val[$i]['general_information'], 'created_at' => $val[$i]['created_at'], 'updated_at' => $val[$i]['updated_at']];
                $arrayResult[] = $merge;
            }
        }

        return $arrayResult;
    }

    public function getTimeDetailForEachUser()
    {
        $date1 = $date2 = $date3 = $date4 = $date5 = $date6 = $date7 = $date8 = $date9 = $date10 = $date11 = $date12 = NULL;
        $registration = Registration::where('user_id', $this->user_id)->get();

        for ($i = 0; $i < count($registration); $i++) {
            $time = TimeAbsence::where('registration_id', $registration[$i]->id)->whereYear('time_details', $this->year)->get();
            for ($j = 0; $j < count($time); $j++) {
                $month = Carbon::parse($time[$j]['time_details'])->format('m');

                switch ($month) {
                    case '01':
                        $date1[] = $time[$j]['time_details'] . '-' . $time[$j]['at_time'];
                        break;
                    case '02':
                        $date2[] = $time[$j]['time_details'] . '-' . $time[$j]['at_time'];
                        break;
                    case '03':
                        $date3[] = $time[$j]['time_details'] . '-' . $time[$j]['at_time'];
                        break;
                    case '04':
                        $date4[] = $time[$j]['time_details'] . '-' . $time[$j]['at_time'];
                        break;
                    case '05':
                        $date5[] = $time[$j]['time_details'] . '-' . $time[$j]['at_time'];
                        break;
                    case '06':
                        $date6[] = $time[$j]['time_details'] . '-' . $time[$j]['at_time'];
                        break;
                    case '07':
                        $date7[] = $time[$j]['time_details'] . '-' . $time[$j]['at_time'];
                        break;
                    case '08':
                        $date8[] = $time[$j]['time_details'] . '-' . $time[$j]['at_time'];
                        break;
                    case '09':
                        $date9[] = $time[$j]['time_details'] . '-' . $time[$j]['at_time'];
                        break;
                    case '10':
                        $date10[] = $time[$j]['time_details'] . '-' . $time[$j]['at_time'];
                        break;
                    case '11':
                        $date11[] = $time[$j]['time_details'] . '-' . $time[$j]['at_time'];
                        break;
                    case '12':
                        $date12[] = $time[$j]['time_details'] . '-' . $time[$j]['at_time'];
                        break;

                    default:
                        # code...
                        break;
                }

            }

        }
        $result = ['January' => $date1, 'February' => $date2, 'March' => $date3, 'April' => $date4, 'May' => $date5, 'June' => $date6, 'July' => $date7, 'August' => $date8, 'September' => $date9, 'October' => $date10, 'November' => $date11, 'December' => $date12];
        return $result;
    }
}
