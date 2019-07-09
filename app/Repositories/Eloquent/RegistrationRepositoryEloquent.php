<?php

namespace App\Repositories\Eloquent;

use App\Mail\SendMailable;
use App\Mail\UpdateMessageMailable;
use App\Mail\UpdateMailable;
use App\Models\Registration;
use App\Models\Type;
use App\Presenters\RegistrationPresenter;
use App\Repositories\Contracts\RegistrationRepository;
use App\Services\ApproverService;
use App\Services\TimeAbsenceService;
use App\Services\TrackService;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Mail;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class RegistrationRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class RegistrationRepositoryEloquent extends BaseRepository implements RegistrationRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Registration::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return RegistrationPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function search(array $attributes)
    {
        // dd($attributes['time']);
        $id = Auth::user()->id;
        $search = $this->model()::where('user_id', $id)->select('id')->get();
        // dd($search);
        $time = TimeAbsenceService::search($search, $attributes);
        $result = $this->model()::whereIn('id', $time)->get();
        return $this->parserResult($result);
    }
    public function searchPending(array $attributes)
    {
        // dd($attributes['time']);
        $id = Auth::user()->id;
        $search = $this->model()::where('user_id', $id)->where('status', 3)->select('id')->get();
        // dd($search);
        $time = TimeAbsenceService::search($search, $attributes);
        $result = $this->model()::whereIn('id', $time)->get();
        return $this->parserResult($result);
    }

    public function searchApproved(array $attributes)
    {
        // dd($attributes['time']);
        $id = Auth::user()->id;
        $search = $this->model()::where('user_id', $id)->where('status', 1)->select('id')->get();
        // dd($search);
        $time = TimeAbsenceService::search($search, $attributes);
        $result = $this->model()::whereIn('id', $time)->get();
        return $this->parserResult($result);
    }

    public function searchDisApproved(array $attributes)
    {
        // dd($attributes['time']);
        $id = Auth::user()->id;
        $search = $this->model()::where('user_id', $id)->where('status', 2)->select('id')->get();
        // dd($search);
        $time = TimeAbsenceService::search($search, $attributes);
        $result = $this->model()::whereIn('id', $time)->get();
        return $this->parserResult($result);
    }

    public function getWorkdays($date1, $date2, $workSat = false, $patron = null)
    {
        if (!defined('SATURDAY')) {
            define('SATURDAY', 6);
        }

        if (!defined('SUNDAY')) {
            define('SUNDAY', 0);
        }

        // Array of all public festivities
        $publicHolidays = array('01-01', '04-30', '05-01', '09-02');
        // The Patron day (if any) is added to public festivities
        if ($patron) {
            $publicHolidays[] = $patron;
        }

        $start = strtotime($date1);
        $end = strtotime($date2);
        $workdays = 0;
        for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
            $day = date("w", $i); // 0=sun, 1=mon, ..., 6=sat
            $mmgg = date('m-d', $i);
            if ($day != SUNDAY &&
                !in_array($mmgg, $publicHolidays) &&

                !($day == SATURDAY && $workSat == false)) {
                $workdays++;
            }
        }

        return intval($workdays);
    }

    public function create(array $attributes)
    {

        // $daystart = $attributes['time_off_beginning'];
        // $dayend = $attributes['time_off_ending'];
        // $holiday = $this->getWorkdays($daystart, $dayend);
        // // dd($holiday);
        // // $check = $this->model()::where('user_id', $attributes['user_id'])->exists();
        // $latest = $this->model()::where('user_id', $attributes['user_id'])
        //     ->where(function ($query) use ($daystart, $dayend) {
        //         $query->where([['time_off_beginning', '<=', $daystart], ['time_off_ending', '>=', $dayend]])
        //             ->orwhere([['time_off_beginning', '>=', $daystart], ['time_off_beginning', '<=', $dayend]])
        //             ->orwhere([['time_off_ending', '>=', $daystart], ['time_off_ending', '<=', $dayend]]);
        //     })->get();
        // $days = $latest->count();
        // $attributes['status'] = 0;
        // if($attributes['type_id'] == 1) {
        //     $attributes['annual_leave_total'] = 12 - $attributes['absence_days'];
        // }
        // $timestart = new Carbon($attributes['time_start']);
        // $timeend = new Carbon($attributes['time_end']);
        // $check = Registration::where('user_id', $attributes['user_id'])->first();

        // $checkTime = TimeAbsence::where('registration_id', $attributes['id'])
        // dd($check->user_id);

        if ($attributes['type'] == 'Từ ngày đến hết ngày') {
            $attributes['status'] = 3;
            if ($attributes['status'] == 3) {
                $attributes['requested_date'] = Carbon::now()->toDateString();
            }
        } else {
            $attributes['status'] = 3;
            if ($attributes['status'] == 3) {
                $attributes['requested_date'] = Carbon::now()->toDateString();
            }
        }

        $res = parent::create($attributes);
        $date = $this->model()::where('user_id', $attributes['user_id'])->select('id')->get();
        $id = array();
        for ($i = 0; $i < count($date); $i++) {
            $id[] = $date[$i]->id;
        }

        $timeabsence = TimeAbsenceService::add($res['data']['id'], $attributes);
        $approver_id = ApproverService::add($res['data']['id'], $attributes);
        // $cc = ApproverService::carbonCopy($res['data']['id'], $attributes);
        $track = TrackService::update($res['data']['id'], $attributes);

        //send mail
        $user = Auth::user();
        $type = Type::find($attributes['type_id']);
        if ($attributes['type'] == 'Từ ngày đến hết ngày') {
            $time_start = $attributes['time_start'];
            $time_end = $attributes['time_end'];
            $str = null;

        } else {
            $date = explode(';', $attributes['date']);
            $arr = array();
            $result = '';
            for ($i = 0; $i < count($date); $i++) {
                $at_time = explode(',', $date[$i]);
                $add = "$at_time[0] ($at_time[1])";
                $arr[] = $add;
            }
            $str = implode(', ', $arr);
            $time_start = null;
            $time_end = null;
        }
        $data = [
            'name' => $user->name,
            'type_id' => $type->name,
            'note' => $attributes['note'],
            'type' => $attributes['type'],
            'time_start' => $time_start,
            'time_end' => $time_end,
            'time_off' => $str,
            'to' => $attributes['emails'],
            'cc' => $attributes['cc'],
        ];

        // Mail::send(new SendMailable($data));
        Mail::queue(new SendMailable($data));
        return parent::find($res['data']['id']);
    }

    public function update(array $attributes, $id)
    {
        $error = 'unsuitable';
        $status = $this->model()::where('id', $id)->select('status')->get();
        if ($status[0]->status == 3) {
            $oldName = Auth::user()->name;
            $old = parent::find($id);
            $oldTime = $old['data']['attributes']['time'];
            $oldDayOff = array();
            for ($i = 0; $i < count($oldTime); $i++) {
                $date = new Carbon($oldTime[$i]['time_details']);
                $time = $date->toDateString() . " " . "(" . $oldTime[$i]['at_time'] . ")";
                $oldDayOff[] = $time;
            }
            $oldDayOff = implode(', ', $oldDayOff);
            // dd($oldDayOff);
            $oldTo = $old['data']['attributes']['mailto'];
            $oldCc = $old['data']['attributes']['mailcc'];
            $newMessage = $old['data']['attributes']['message'];
            $oldNote = $old['data']['attributes']['note'];
            $oldType = $old['data']['attributes']['type']['name'];
            $oldTypeAbsence = $old['data']['attributes']['time'][0]['type'];
            // dd($oldTypeAbsence);
            // $data = [
            //     'registerName' => $oldName,
            //     'typeId' => $oldType,
            //     'note' => $oldNote,
            //     'type' => $oldTypeAbsence,
            //     'timeOff' => $oldDayOff,
            //     'to' => $oldTo,
            //     'cc' => $oldCc,
            //     'message' => $newMessage,
            // ];
            $registration = parent::update(array_except($attributes, ['user_id', 'status', 'requested_date']), $id);
            TimeAbsenceService::update($id, $attributes);
            //update email from user;
            $new = parent::find($id);
            $newTime = $new['data']['attributes']['time'];
            $newDayOff = array();
            for ($i = 0; $i < count($newTime); $i++) {
                $date = new Carbon($newTime[$i]['time_details']);
                $time = $date->toDateString() . " " . "(" . $newTime[$i]['at_time'] . ")";
                $newDayOff[] = $time;
            }
            $newDayOff = implode(', ', $newDayOff);
            // dd($newDayOff);
            $newNote = $new['data']['attributes']['note'];
            $newType = $new['data']['attributes']['type']['name'];
            $newTypeAbsence = $new['data']['attributes']['time'][0]['type'];
            $data = [
                'registerName' => $oldName,
                'oldTypeId' => $oldType,
                'oldNote' => $oldNote,
                'oldType' => $oldTypeAbsence,
                'oldTimeOff' => $oldDayOff,
                'typeId' => $newType,
                'note' => $newNote,
                'type' => $newTypeAbsence,
                'timeOff' => $newDayOff,
                'to' => $oldTo,
                'cc' => $oldCc,
                'message' => $newMessage,
            ];
            Mail::queue(new UpdateMailable($data));
            return $new;
        } else {
            return $error;
        }
    }

    public function getPending($email)
    {
        $regis = Registration::whereHas('approvers', function ($query) use ($email) {
            $query->where('email', $email)->where('type', 0);
        })->where('status', 3)->get();
        return $this->parserResult($regis);
    }

    public function searchRegisPending(array $attributes)
    {
        $email = Auth::user()->email;
        $regis = Registration::whereHas('approvers', function ($query) use ($email) {
            $query->where('email', $email)->where('type', 0);
        })->where('status', 3)->select('id')->get();
        $idSearch = TimeAbsenceService::search($regis, $attributes);
        $result = $this->model()::whereIn('id', $idSearch)->get();
        return $this->parserResult($result);
    }

    public function getMessage($id, array $attributes)
    {
        $message = $this->model()::where('id', $id)->update(['message' => $attributes['message']]);
        return $message;
    }

    public function updateMail($id, $user)
    {
        //update mail
        // if($user == 1) {
        //     $update = 'Your absence days are approved.';
        // }
        $info = Auth::user()->name;
        $info_email = Auth::user()->email;
        // dd($user);
        $email = parent::find($id);
        // dd('abc');
        $emailTypeAbsence = $email['data']['attributes']['time'][0]['type'];
        // dd('def');
        $emailType = $email['data']['attributes']['type']['name'];
        $emailNote = $email['data']['attributes']['note'];
        $emailMessage = $email['data']['attributes']['message'];
        $emailRegistration = $email['data']['attributes']['user']['email'];
        $emailName = $email['data']['attributes']['user']['name'];
        $emailTo = $email['data']['attributes']['mailto'];
        $emailCc = $email['data']['attributes']['mailcc'];
        $emailTo = array_unique(array_merge($emailTo, $emailCc));
        // dd($emailTo);
        $timeDetails = $email['data']['attributes']['time'];
        $oldDayOff = array();
        for ($i = 0; $i < count($timeDetails); $i++) {
            $date = new Carbon($timeDetails[$i]['time_details']);
            $time = $date->toDateString() . " " . "(" . $timeDetails[$i]['at_time'] . ")";
            $oldDayOff[] = $time;
        }
        $oldDayOff = implode(', ', $oldDayOff);
        $data = [
            'name' => $info,
            'register_name' => $emailName,
            'type_id' => $emailType,
            'note' => $emailNote,
            'type' => $emailTypeAbsence,
            'time_off' => $oldDayOff,
            'to' => $emailRegistration,
            'cc' => $emailTo,
            'message' => $emailMessage,
            'user' => $user,
            'info_email' => $info_email,
        ];

        // Mail::send(new SendMailable($data));
        Mail::queue(new UpdateMessageMailable($data));
    }
}
