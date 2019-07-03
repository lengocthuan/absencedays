<?php

namespace App\Models;

/**
 * Class Team.
 *
 * @package namespace App\Models;
 */
class Approver extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email'];

    public function registrations() {
        return $this->belongsToMany(\App\Models\Registration::class);
    }
}
