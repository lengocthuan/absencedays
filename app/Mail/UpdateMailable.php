<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateMailable extends Mailable
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
        $subject = '[Nhân sự] Xin ' . $this->data['typeId'];
        $to = $this->data['to'];
        $cc = $this->data['cc'];
        $input = [
            'name' => $this->data['registerName'],
            'typeId' => $this->data['typeId'],
            'oldTypeId' => $this->data['oldTypeId'],
            'note' => $this->data['note'],
            'oldNote' => $this->data['oldNote'],
            'oldType' => $this->data['oldType'],
            'type' => $this->data['type'],
            'timeOff' => $this->data['timeOff'],
            'oldTimeOff' => $this->data['oldTimeOff'],
            'message' => $this->data['message'],
        ];
        // dd('abc');
        return $this->to($to)->cc($cc)->subject("$subject")->view('emails.updateMail')->with(['inputs' => $input]);
        // return $this->to($email)->subject("$subject")->cc('lengocthuan2581997@gmail.com')->view('emails.message')->with(['inputs' => $input]);
        // return $this->cc('hr@greenglobal.vn')->view('emails.message');
    }
}
