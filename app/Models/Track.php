<?php

namespace App\Models;

use App\Models\Registration;
use App\Models\TimeAbsence;
use App\User;
use App\Traits\InformationUserTrait;

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
    protected $fillable = ['user_id', 'year', 'annual_leave_total', 'annual_leave_unused', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

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

}
