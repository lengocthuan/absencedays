<?php

namespace App\Models;

/**
 * Class Position.
 *
 * @package namespace App\Models;
 */
class Position extends App\Models\BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

}
