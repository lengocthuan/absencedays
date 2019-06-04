<?php

namespace App\Models;

/**
 * Class Type.
 *
 * @package namespace App\Models;
 */
class Type extends App\Models\BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'requirement'];

}
