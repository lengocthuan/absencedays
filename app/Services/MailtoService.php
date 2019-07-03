<?php
namespace App\Services;

use App\Models\Registration;

class MailtoService
{
    public static function add($id, $mail)
    {
        $email = explode(",", $mail);
        $username = array();
        for ($i = 0; $i < count($email); $i++) {
            $temp = explode('@', $email[$i]);

            $username = $temp[0];
            $temp = [];

            $mail = Mailto::firstOrCreate(['email' => $email[$i]], ['display_name' => $username]);
            $username = null;
            $arr[] = $mail;
        }
        $br = Bookroom::find($id);
        for ($i = 0; $i < count($arr); $i++) {
            $br->mailtos()->attach($arr[$i]);
        }
        return $br;
    }
    public static function update($id, $mail)
    {
        $email = explode(",", $mail);
        $username = array();
        for ($i = 0; $i < count($email); $i++) {
            $temp = explode('@', $email[$i]);

            $username = $temp[0];
            $temp = [];

            $mail = Mailto::firstOrCreate(['email' => $email[$i]], ['display_name' => $username]);
            $username = null;
            $arr[] = $mail;
        }
        $br = Bookroom::find($id);
        $br->mailtos()->detach();
        for ($i = 0; $i < count($arr); $i++) {
            $br->mailtos()->syncWithoutDetaching($arr[$i]);
        }
        return $br;
    }
    public static function delete($id)
    {
        $br = Bookroom::find($id);
        $br->mailtos()->detach();
    }
}
