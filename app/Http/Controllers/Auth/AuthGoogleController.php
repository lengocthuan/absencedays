<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Socialite;
use App\Services\RoleService;
use App\Models\Social;
use App\Models\DeviceToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthGoogleRequest;
use Carbon\Carbon;

class AuthGoogleController extends Controller
{
    public function login(AuthGoogleRequest $request)
    {
        $provider = Social::PROVIDER_GOOGLE;

        $profile = Socialite::driver($provider)->userFromToken($request->token);

        if (!$profile->email) {
            throw new \Illuminate\Validation\UnauthorizedException('Invalid email');
        }

        $social = Social::firstOrNew([ //firstOrCreate()
            'social_name' => $provider,
            'social_id' => $profile->id,
        ]);

        if ($social->user_id) {
            $user = User::find($social->user_id);
        } else {
            $user = User::where(['email' => $profile->email])->first();
            if($user) RoleService::add($user, 'member'); //create role_user when login the first
            if (!$user) {
                // $user = new User;
                // $user->name = $profile->name;
                // $user->email = $profile->email;
                // $user->password = bcrypt($profile->id . time());
                // $user->first_workday = Carbon::now()->toDateString();
                // $user->team_id = 1;
                // $user->position_id = 1;
                // $user->save();
                // RoleService::add($user, 'member');
                return response()->json('You cant login in system because you not a member.', 404);
            }
            $social->user_id = $user->id;
            $social->save();
        }

        $device_uuid = $request->input('device_uuid');
        $device_token = $request->input('device_token');

        if ($device_uuid && $device_token) {
            $uuid = DeviceToken::where('uuid', $device_uuid)->first();
            if ($uuid) {
                //update token device
                $uuid->user_id = auth()->user()->id;
                $uuid->token = $device_token;
                $uuid->save();
            } else {
                $device = new DeviceToken();
                $device->user_id = auth()->user()->id;
                $device->uuid = $device_uuid;
                $device->token = $device_token;
                $device->save();
            }
        }

        $token = auth()->fromUser($user);

        return response()->json(formatToken($token));
    }
}
