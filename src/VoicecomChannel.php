<?php

namespace Boyo\Voicecom;

use Illuminate\Notifications\Notification;
use Boyo\Voicecom\VoicecomSender;
use Boyo\Voicecom\VoicecomMessage;

class VoicecomChannel
{
	
    protected $client;
    
    /**
     * @param ClickatellClient $clickatell
     */
    public function __construct(VoicecomSender $sender)
    {
        $this->client = $sender;
    }
    
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        
        $message = $notification->toVoicecom($notifiable);
        
        if (!$message instanceof VoicecomMessage) {
	        throw new \Exception('No message provided');
	    }
	    
	    $message->build();
	    
        $this->client->send($message);
        
    }
    
    
}