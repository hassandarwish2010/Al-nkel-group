<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUserNotification extends Mailable
{
    use Queueable, SerializesModels;
    protected $data=[];
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data=[])
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      //  return $this->subject('new reservation')->view('emails.newreserve')->with('data',$this->data)->priority(1);
       
      return $this->from('mail@example.com', 'Mailtrap')
      ->subject('Mailtrap Confirmation')
      ->markdown('emails.newreserve')
      ->with([
          'name' => 'New Mailtrap User',
          'link' => 'https://mailtrap.io/inboxes'
      ]);
    }

}
