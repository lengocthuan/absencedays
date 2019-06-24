<?php

namespace App\Models;

use App\Models\Registration;
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
    protected $fillable = ['registration_id', 'type', 'time_details', 'at_time', 'current_year', '  absence_days', 'annual_leave_total', 'annual_leave_unused', 'general_information'];

    public function getTimeAbsence() {
        return $this->belongsTo(\App\Models\Registration::class, 'registration_id');
    }


}
