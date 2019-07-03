<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        $subject = '[Nhân sự] Xin ' . $this->data['type_id'];
        $input = [
            'name' => $this->data['name'],
            'type_id' => $this->data['type_id'],
            'reason' => $this->data['note'],
            'type_registration' => $this->data['type'],
            'timestart' => $this->data['time_start'],
            'timeend' => $this->data['time_end'],
            'timeoff' => $this->data['time_off'],
        ];
        $email = ['comebacktohero@gmail.com', 'comebacktohero1@gmail.com'];
        /*
        Mail::send('emails.welcome', [], function($message) use ($emails)
        {    
            $message->to($emails)->subject('This is test e-mail');    
        });
        */
        return $this->to($email)->subject("$subject")->cc('lengocthuan2581997@gmail.com')->view('emails.message')->with(['inputs' => $input]);
        // return $this->cc('hr@greenglobal.vn')->view('emails.message');
    }
}
