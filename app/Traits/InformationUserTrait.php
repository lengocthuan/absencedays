<?php

namespace App\Traits;

use App\User;

trait InformationUserTrait
{
    public function InfoUser($id)
    {
        $informationUser = User::where('id', $id)->get();
        $result = array();
        $merge = array();
        foreach ($informationUser as $value) {
            $temp = ['id' => $value->id, 'name' => $value->name, 'phone' => $value->phone, 'address' => $value->address, 'email' => $value->email, 'first_workday' => $value->first_workday, 'team' => $value->getTeam->name, 'position' => $value->getPosition->name, 'avatar' => $value->avatar, 'created_at' => $value->created_at, 'updated_at' => $value->updated_at];
            $result[] = $temp;
            $resultMerge = ['id' => $result[0]['id'], 'name' => $result[0]['name'], 'phone' => $result[0]['phone'], 'address' => $result[0]['address'], 'email' => $result[0]['email'], 'first_workday' => $result[0]['first_workday'], 'team' => $result[0]['team'], 'position' => $result[0]['position'], 'avatar' => $result[0]['avatar'], 'created_at' => $result[0]['created_at'], 'updated_at' => $result[0]['updated_at']];
        }

        return $resultMerge;
    }
}
