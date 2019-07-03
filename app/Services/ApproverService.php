<?php
namespace App\Services;

use App\User;
use App\Models\Approver;
use App\Models\Registration;

class ApproverService
{
    public static function add($id , array $atrributes)
    {
        $approver = Registration::find($id);

        $cut = explode(',', $atrributes['emails']);
        $arr = array();
        for ($i=0; $i < count($cut); $i++) { 
            $mails = Approver::firstOrCreate(['email' => $cut[$i]]);
            $arr[] = $mails;
        }

        for ($i=0; $i < count($arr) ; $i++) { 
            $approver->approvers()->attach($arr[$i]);
        }
        return $approver;
    }

    // public static function show()
    // {
        
    // }
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
