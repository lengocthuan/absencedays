<?php

namespace App\Models;

/**
 * Class Registration.
 *
 * @package namespace App\Models;
 */
class Registration extends App\Models\BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'type_id', 'note', 'status', 'requested_date', 'approved_date', 'time_off_beginning', 'time_off_ending', 'current_year', 'annual_leave_total', 'annual_leave', 'annual_leave_unused', 'sick_leave', 'marriage_leave', 'maternity_leave', 'bereavement_leave', 'long_term_unpaid_leave', 'short_term_unpaid_leave'];

}
