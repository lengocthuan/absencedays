<?php

namespace App\Models;

/**
 * Class Team.
 *
 * @package namespace App\Models;
 */
class Team extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

}
