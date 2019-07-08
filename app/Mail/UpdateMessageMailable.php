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
        // $to = explode(',', $this->data['to']);
        $to = $this->data['to'];
        // dd($to);
        $cc = $this->data['cc'];
        // $cc = explode(',', $this->data['cc']);
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
        // dd('abc');
        return $this->to($to)->cc($cc)->subject("$subject")->view('emails.updateMessage')->with(['inputs' => $input]);
        // return $this->to($email)->subject("$subject")->cc('lengocthuan2581997@gmail.com')->view('emails.message')->with(['inputs' => $input]);
        // return $this->cc('hr@greenglobal.vn')->view('emails.message');
    }
}
