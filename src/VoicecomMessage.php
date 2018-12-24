<?php

namespace Boyo\Voicecom;

use Illuminate\Notifications\Notification;

class VoicecomMessage
{
	/**
     * The phone number to send the message to
     *
     * @var string
     */
    public $to = '';
    
    /**
     * The message content.
     *
     * @var string
     */
    public $message = '';
    
    /**
     * The sms id
     *
     * @var string
     */
    public $id = '';
    
    /**
     * @param  string $message
     * @param  string $id
     */
    public function __construct($id, $message = '')
    {
        $this->message = $message;
        $this->id = $id;
    }
    
    /**
     * Use this method to set custom content in messages
     *
     *
     * @return $this
     */
    public function build() {
	    
	    return $this;
	    
    }
    
    /**
     * Set the phone number of the recipient
     *
     * @param  string $to
     *
     * @return $this
     */
    public function to(string $to) {
	    
	    $this->to = $to;
	    
	    return $this;
    }
}