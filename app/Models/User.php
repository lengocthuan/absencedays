<?php

namespace App\Models;

use App\Team;
use App\Position;
/**
 * Class User.
 *
 * @package namespace App\Models;
 */
class User extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'team_id', 'position_id', 'phone', 'address', 'email', 'password', 'first_workday'];



}
