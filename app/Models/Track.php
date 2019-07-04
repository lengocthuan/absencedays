<?php

namespace App\Models;

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
    protected $fillable = ['user_id', 'year', 'annual_leave_total', 'annual_leave_unused', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
}
