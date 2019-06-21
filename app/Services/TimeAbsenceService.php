<?php
namespace App\Services;

use App\Models\Registration;
use App\Models\TimeAbsence;

class TimeAbsenceService
{
    public static function add($registration_id, $sum)
    {
        $registration = Registration::find($registration_id);
        $registration->absence_days = $sum;
        $registration->save();
    }

    public static function addTime($time_details)
    {
        $time = explode(';', $time_details);
        for ($i = 0; $i < count($time); $i++) {
            $time_children = explode(',', $time[$i]);

            $details = new TimeAbsence;
            $details->registration_id = $time_children[0];
            $details->type = $time_children[1];
            $details->time_details = $time_children[2];
            $details->at_time = $time_children[3];
            $details->save();

        }
    }
    // public static function sync(User $user, $roleName)
    // {
    //     if (!$roleName) {
    //         throwError('Please insert role name', 422);
    //     }
    //     if (!empty($roleName) && !in_array($roleName, Role::roles())) {
    //         throwError('Some thing went wrong!', 500);
    //     }
    //     // find or create role admin
    //     $role = Role::firstOrCreate(['name' => $roleName]);

    //     return $user->roles()->sync($role);
    // }
}
