<?php

namespace App\Models;

use App\Models\TimeAbsence;
use App\Models\Type;
use App\User;
use App\Traits\InformationUserTrait;

/**
 * Class Registration.
 *
 * @package namespace App\Models;
 */
class Registration extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use InformationUserTrait;

    const TYPE_ABSENCE = 'Từ ngày đến hết ngày';
    const FULL = 'Cả Ngày';
    const MORNING = 'Buổi Sáng';
    const AFTERNOON = 'Buổi Chiều';
    const DUPLICATE_TIME = 'Duplicate Time Registration';

    protected $table = 'registrations';
    protected $fillable = ['user_id', 'type_id', 'note', 'status', 'requested_date', 'approved_date', 'message'];


    public function approvers()
    {
        return $this->belongsToMany(\App\Models\Approver::class)->withTimestamps();
    }

    public function getUser()
    {
        return $this->InfoUser($this->user_id);
    }

    public function getType()
    {
        return $this->belongsTo(\App\Models\Type::class, 'type_id');
    }

    public function getTimeAbsence()
    {
        $timeAbsence = $this->hasMany(\App\Models\TimeAbsence::class, 'registration_id');
        if(empty($timeAbsence)) {
            return $timeAbsence = null;
        }
        return $timeAbsence;
    }

    public function getTotalTime()
    {
        $total = TimeAbsence::where('registration_id', $this->id)->select('absence_days')->get();
        $sum = 0;
        foreach ($total as $value) {
            $sum += $value->absence_days;
        }
        return $sum;
    }

    public function getMailto() {
        return $this->approvers()->where('type', 0)->pluck('email')->toArray();
    }

    public function getMailcc() {
        return $this->approvers()->where('type', 1)->pluck('email')->toArray();
    }

}
