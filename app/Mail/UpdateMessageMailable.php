<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateMessageMailable extends Mailable
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
        $subject = '[Nhân sự] Xin ' . $this->data['type_id'];
        $mailTo = $this->data['to'];
        $mailCc = $this->data['cc'];
        $input = [
            'name' => $this->data['name'],
            'type_id' => $this->data['type_id'],
            'reason' => $this->data['note'],
            'type_registration' => $this->data['type'],
            'timeoff' => $this->data['time_off'],
            'message' => $this->data['message'],
            'register_name' => $this->data['register_name'],
            'approver' => $this->data['info_email'],
            'user' => $this->data['user'],
        ];
        return $this->to($mailTo)->cc($mailCc)->subject("$subject")->view('emails.update-message')->with(['inputs' => $input]);
    }
}
