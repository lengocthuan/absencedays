<?php

namespace App\Models;

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
    protected $fillable = ['registration_id', 'type', 'time_details', 'at_time', 'time_start', 'time_end'];

}
