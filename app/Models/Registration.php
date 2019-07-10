<?php

namespace App\Models;

use App\Models\TimeAbsence;
use App\Models\Type;
use App\User;

/**
 * Class Registration.
 *
 * @package namespace App\Models;
 */
class Registration extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'registrations';
    protected $fillable = ['user_id', 'type_id', 'note', 'status', 'requested_date', 'approved_date', 'message'];

    public function approvers()
    {
        return $this->belongsToMany(\App\Models\Approver::class)->withTimestamps();
    }
    public function getUser()
    {
        // $user = User::find($this->user_id);
        // return $user->name;
        // return $this->belongsTo(\App\User::class, 'user_id');
        $informationUser = User::where('id', $this->user_id)->get();
        $result = array();
        $merge = array();
        foreach ($informationUser as $value) {
            // dd('abc');
            $temp = ['id' => $value->id, 'name' => $value->name, 'phone' => $value->phone, 'address' => $value->address, 'email' => $value->email, 'first_workday' => $value->first_workday, 'team' => $value->getTeam->name, 'position' => $value->getPosition->name, 'avatar' => $value->avatar, 'created_at' => $value->created_at, 'updated_at' => $value->updated_at];
            $result[] = $temp;
            $resultMerge = ['id' => $result[0]['id'], 'name' => $result[0]['name'], 'phone' => $result[0]['phone'], 'address' => $result[0]['address'], 'email' => $result[0]['email'], 'first_workday' => $result[0]['first_workday'], 'team' => $result[0]['team'], 'position' => $result[0]['position'], 'avatar' => $result[0]['avatar'], 'created_at' => $result[0]['created_at'], 'updated_at' => $result[0]['updated_at']];
        }
        return $resultMerge;
    }

    public function getType()
    {
        // $type = Type::find($this->type_id);
        // return $type->name;
        return $this->belongsTo(\App\Models\Type::class, 'type_id');
    }

    public function getTimeAbsence()
    {
        $timeAB = $this->hasMany(\App\Models\TimeAbsence::class, 'registration_id');
        if(empty($timeAB)) {
            return $timeAB = null;
        }
        return $timeAB;
    }

    public function getTotalTime()
    {
        // $this->hasMany(\App\Models\TimeAbsence::class, 'registration_id');
        // $total = $this->getTimeAbsence;
        // dd($total);
        // $user = User::find($this->user_id);
        // return $user->name;
        $total = TimeAbsence::where('registration_id', $this->id)->select('absence_days')->get();
        // dd($total);
        $sum = 0;
        foreach ($total as $value) {
            $sum += $value->absence_days;
        }
        return $sum;
    }

    // public function getApprover()
    // {
    //     $approver = Registration::where('id', $this->id)->select('approver_id')->get();
    //     $app = explode(',', $approver[0]->approver_id); //array 0->2 ; 1->3
    //     $arr = [];
    //     foreach ($app as $value) {
    //         $info = User::where('id', $value)->select('id', 'name', 'email')->get();
    //         $r = ['id' => $info[0]['id'], 'name' => $info[0]['name'], 'email' => $info[0]['email']];
    //         $arr[] = $r;
    //     }
    //     return $arr;
    // }
    public function getMailto() {
        return $this->approvers()->where('type', 0)->pluck('email')->toArray();
    }

    public function getMailcc() {
        return $this->approvers()->where('type', 1)->pluck('email')->toArray();
    }
}
