<?php

namespace App\Models;

/**
 * Class Team.
 *
 * @package namespace App\Models;
 */
class Team extends App\Models\BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

}
