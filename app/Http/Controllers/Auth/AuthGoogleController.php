<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthGoogleRequest;
use App\Models\DeviceToken;
use App\Models\Social;
use App\Services\RoleService;
use App\Services\TrackService;
use App\User;
use Socialite;

class AuthGoogleController extends Controller
{
    // public function login(AuthGoogleRequest $request)
    public function login(AuthGoogleRequest $request)
    {
        $provider = Social::PROVIDER_GOOGLE;

        $profile = Socialite::driver($provider)->userFromToken($request->token);

        if (!$profile->email) {
            throw new \Illuminate\Validation\UnauthorizedException('Invalid email');
        }

        $social = Social::firstOrNew([
            'social_name' => $provider,
            'social_id' => $profile->id,
        ]);

        if ($social->user_id) {
            $user = User::find($social->user_id);
            if ($user) {
                $user1 = User::where(['email' => $profile->email])->first();
                if (!$user1->hasrole(['member', 'super_admin', 'project_management', 'tech_lead'])) {
                    RoleService::add($user, 'member'); //check role for user when login;
                }
                User::where('email', $profile->email)->update(['avatar' => $profile->avatar]);
                $id = User::where('email', $profile->email)->select('id')->get();
                TrackService::create($id[0]->id);
            }
        } else {
            $user = User::where(['email' => $profile->email])->first();
            if ($user) {
                if (!$user->hasrole(['member', 'super_admin', 'project_management', 'tech_lead'])) {
                    RoleService::add($user, 'member'); //check role for user when login;
                }
                User::where('email', $profile->email)->update(['avatar' => $profile->avatar]);
                $id = User::where('email', $profile->email)->select('id')->get();
                TrackService::create($id[0]->id);
            }
            if (!$user) {
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
