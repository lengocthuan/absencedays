<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->data = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(!is_null($this->data['timeStart'])) {
            $convertTimeStart = Carbon::parse($this->data['timeStart'])->format('d/m/Y');
        } else $convertTimeStart = null;
        
        $subject = '[Nhân sự] Xin ' . $this->data['typeId'] . '-' . $convertTimeStart . $this->data['firstDayOff'];
        $mailTo = explode(',', $this->data['to']);
        $mailCc = explode(',', $this->data['cc']);
        $input = [
            'name' => $this->data['name'],
            'typeId' => $this->data['typeId'],
            'reason' => $this->data['note'],
            'typeAbsence' => $this->data['type'],
            'timeStart' => $this->data['timeStart'],
            'timeEnd' => $this->data['timeEnd'],
            'timeOff' => $this->data['timeOff'],
        ];
        return $this->to($mailTo)->cc($mailCc)->subject("$subject")->view('emails.message')->with(['inputs' => $input]);
    }
}
