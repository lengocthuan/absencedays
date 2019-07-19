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
        $id = Auth::id();
        switch ($attributes['status']) {
            case '1':
                $search = $this->model()::where('user_id', $id)->where('status', 1)->select('id')->get();
                break;
            case '2':
                $search = $this->model()::where('user_id', $id)->where('status', 2)->select('id')->get();
                break;
            case '3':
                $search = $this->model()::where('user_id', $id)->where('status', 3)->select('id')->get();
                break;
            
            default:
                $search = $this->model()::where('user_id', $id)->select('id')->get();
                break;
        }
        $time = TimeAbsenceService::search($search, $attributes);
        $result = $this->model()::whereIn('id', $time)->get();
        return $this->parserResult($result);
    }

    public static function getWorkdays($date1, $date2, $workSat = false, $patron = null)
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
        if ($attributes['type'] == Registration::TYPE_ABSENCE) {
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

        $resgistration = parent::create($attributes);
        $date = $this->model()::where('user_id', $attributes['user_id'])->select('id')->get();
        $id = array();
        for ($i = 0; $i < count($date); $i++) {
            $id[] = $date[$i]->id;
        }

        $addMail = ApproverService::add($resgistration['data']['id'], $attributes);
        if(!$addMail) {
            return false;
        }
        $addTimeDetails = TimeAbsenceService::add($resgistration['data']['id'], $attributes);
        if(!is_null($addTimeDetails)) {
            return Registration::DUPLICATE_TIME;
        }
        TrackService::update($resgistration['data']['id'], $attributes);

        //send mail
        $user = Auth::user();
        $type = Type::find($attributes['type_id']);
        if ($attributes['type'] == Registration::TYPE_ABSENCE) {
            $timeStart = $attributes['time_start'];
            $timeEnd = $attributes['time_end'];
            $merge = null;
            $firstDayOff = null;
        } else {
            $date = explode(';', $attributes['date']);
            $arrayDate = array();
            for ($i = 0; $i < count($date); $i++) {
                $atTime = explode(',', $date[$i]);
                $add = "$atTime[0] ($atTime[1])";
                $arrayDate[] = $add;
            }
            $cutArrayDate = explode(' ', $arrayDate[0]);
            $firstDayOff = Carbon::parse($cutArrayDate[0])->format('d/m/Y');
            $merge = implode(', ', $arrayDate);
            $timeStart = null;
            $timeEnd = null;
        }

        $data = [
            'name' => $user->name,
            'typeId' => $type->name,
            'note' => $attributes['note'],
            'type' => $attributes['type'],
            'timeStart' => $timeStart,
            'timeEnd' => $timeEnd,
            'timeOff' => $merge,
            'to' => $attributes['emails'],
            'cc' => $attributes['cc'],
            'firstDayOff' => $firstDayOff,
        ];

        Mail::queue(new SendMailable($data));
        return parent::find($resgistration['data']['id']);
    }

    public function update(array $attributes, $id)
    {
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
            $oldTo = $old['data']['attributes']['mailto'];
            $oldCc = $old['data']['attributes']['mailcc'];
            $newMessage = $old['data']['attributes']['message'];
            $oldNote = $old['data']['attributes']['note'];
            $oldType = $old['data']['attributes']['type']['name'];
            $oldTypeAbsence = $old['data']['attributes']['time'][0]['type'];

            $registration = parent::update(array_except($attributes, ['user_id', 'status', 'requested_date']), $id);
            TimeAbsenceService::delete($id);
            TimeAbsenceService::add($id, $attributes);
            //update email from user;
            $new = parent::find($id);
            $newTime = $new['data']['attributes']['time'];
            $newDayOff = array();
            for ($i = 0; $i < count($newTime); $i++) {
                $date = new Carbon($newTime[$i]['time_details']);
                $time = $date->toDateString() . " " . "(" . $newTime[$i]['at_time'] . ")";
                $newDayOff[] = $time;
            }
            $cutDate = explode(' ', $newDayOff[0]);
            $showTitleTime = Carbon::parse($cutDate[0])->format('d/m/Y');
            $newDayOff = implode(', ', $newDayOff);
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
                'titleTime' => $showTitleTime,
            ];
            Mail::queue(new UpdateMailable($data));
            return $new;
        } else {
            return false;
        }
    }

    public function getPending($email)
    {
        $registration = Registration::whereHas('approvers', function ($query) use ($email) {
            $query->where('email', $email)->where('type', 0);
        })->where('status', 3)->get();
        return $this->parserResult($registration);
    }

    public function searchRegistrationPending(array $attributes)
    {
        $email = Auth::user()->email;
        $registration = Registration::whereHas('approvers', function ($query) use ($email) {
            $query->where('email', $email)->where('type', 0);
        })->where('status', 3)->select('id')->get();
        $idSearch = TimeAbsenceService::search($registration, $attributes);
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
        $info = Auth::user()->name;
        $infoEmail = Auth::user()->email;
        $email = parent::find($id);
        $emailTimeAbsence = $email['data']['attributes']['time'];

        if(count($emailTimeAbsence) != 0) {
            $emailTypeAbsence = $email['data']['attributes']['time'][0]['type'];
        } else return trans('message.registration.timeRegistrationNotExist');

        $emailType = $email['data']['attributes']['type']['name'];
        $emailNote = $email['data']['attributes']['note'];
        $emailMessage = $email['data']['attributes']['message'];
        $emailRegistration = $email['data']['attributes']['user']['email'];
        $emailName = $email['data']['attributes']['user']['name'];
        $emailTo = $email['data']['attributes']['mailto'];
        $emailCc = $email['data']['attributes']['mailcc'];
        $emailTo = array_unique(array_merge($emailTo, $emailCc));
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
            'info_email' => $infoEmail,
        ];
        Mail::queue(new UpdateMessageMailable($data));
    }
}
