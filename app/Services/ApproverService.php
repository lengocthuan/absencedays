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

        $cutMailTo = explode(',', $atrributes['emails']);
        $cutMailCc = explode(',', $atrributes['cc']);

        for ($i=0; $i < count($cutMailTo) ; $i++) {
            for ($j=0; $j < count($cutMailCc); $j++) { 
                if($cutMailTo[$i] == $cutMailCc[$j]) {
                    return false; //compare mail to and cc and not allow matched;
                }
            }
        }

        for ($i=0; $i < count($cutMailTo); $i++) { 
            $mails = Approver::create(['email' => $cutMailTo[$i], 'type' => 0]);
            $arrayMailTo[] = $mails;
        }

        for ($i=0; $i < count($cutMailCc); $i++) { 
            $mailCc = Approver::create(['email' => $cutMailCc[$i], 'type' => 1]);
            $arrayMailCc[] = $mailCc;
        }

        for ($i=0; $i < count($arrayMailTo) ; $i++) { 
            $approver->approvers()->attach($arrayMailTo[$i]);
        }

        for ($i=0; $i < count($arrayMailCc) ; $i++) {
            $approver->approvers()->attach($arrayMailCc[$i]);
        }

        return $approver;

    }

}
