<?php

namespace App\Models\Trust;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    const SUPER_ADMIN = 'super_admin';
    const PM = 'project_management';
    const TECH_LEAD = 'tech_lead';
    const MEMBER = 'member';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'decription'];

    /**
     * @return array List roles
     */
    public static function roles()
    {
        return [self::SUPER_ADMIN, self::PM, self::TECH_LEAD, self::MEMBER];
    }

    // public function users()
    // {
    //     return $this->belongsToMany('App\User');
    // }
}
