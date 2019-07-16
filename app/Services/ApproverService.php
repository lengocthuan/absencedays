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
        $arrayMailTo = array();
        for ($i=0; $i < count($cutMailTo); $i++) { 
            $mails = Approver::firstOrCreate(['email' => $cutMailTo[$i]]);
            $arrayMailTo[] = $mails;
        }

        $cutMailCc = explode(',', $atrributes['cc']);
        $arrayMailCc = array();
        for ($i=0; $i < count($cutMailCc); $i++) { 
            $mailCc = Approver::firstOrCreate(['email' => $cutMailCc[$i]]);
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
