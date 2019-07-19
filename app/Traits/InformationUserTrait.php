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
            $resultMerge = $value->attributes;
            $resultMerge['team'] = $value->getTeam->name;
            $resultMerge['position'] = $value->getPosition->name;
            unset($resultMerge['team_id']);
            unset($resultMerge['position_id']);
        }

        return $resultMerge;
    }
}
