<?php

namespace App\Models;

use App\Models\Registration;
use App\User;
use App\Traits\InformationUserTrait;
use App\Models\TimeAbsence;
/**
 * Class TimeAbsence.
 *
 * @package namespace App\Models;
 */
class TimeAbsence extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use InformationUserTrait;

    protected $fillable = ['registration_id', 'type', 'time_details', 'at_time', 'current_year', 'absence_days', 'annual_leave_total', 'annual_leave_unused', 'general_information'];

    public function getInfoUser()
    {
        $user = Registration::where('id', $this->registration_id)->get();
        for ($i=0; $i < count($user); $i++) { 
            $user_id = User::where('id', $user[$i]->user_id)->get();
        }

        return $this->InfoUser($user_id[0]->id);
    }

    public function getInfoRegistration()
    {
        $timeAbsence = Registration::where('id', $this->registration_id)->select('type_id', 'note')->get();
        for ($i=0; $i < count($timeAbsence); $i++) { 
            $type = ['type' => $timeAbsence[$i]->getType->name, 'note' => $timeAbsence[$i]->note];
        }
        return $type;
    }
}
