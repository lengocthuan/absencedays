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
        $mailTo = $this->data['to'];
        $mailCc = $this->data['cc'];
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
        return $this->to($mailTo)->cc($mailCc)->subject("$subject")->view('emails.update-mail')->with(['inputs' => $input]);
    }
}
