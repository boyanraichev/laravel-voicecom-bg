<?php

namespace Boyo\Voicecom;

use Boyo\Voicecom\Exceptions\CouldNotSendMessage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Bulglish;

class VoicecomSender
{
	private $log = true;
	
	private $log_channel = 'stack';
	
	private $send = false;
	
	private $url = 'https://bsms.voicecom.bg/smsapi/bsms/sendsms/';	
	
	private $encoding = 'gsm-03-38';
	
	private $priority = '2';
	
	private $sid = '';
	
	private $prefix = '';
	
	// construct
	public function __construct() {
		
		// settings
		$this->sid = config('services.voicecom.sid');
		$this->driver = config('services.voicecom.driver');
		$this->log = config('services.voicecom.log');
		$this->send = config('services.voicecom.send');
		$this->log_channel = config('services.voicecom.log_channel');
		
		// setup Guzzle client
		$this->client = new Client(['base_uri' => $this->url]);
		
	}
	
	private function processTel($tel) {
		$tel = preg_replace('/^\+/', '0', $tel);
		$tel = preg_replace('/[^0-9]/', '', $tel);
		$tel = preg_replace('/^00/', '0', $tel);
		$tel = preg_replace('/^0359/', '359', $tel);
		$tel = preg_replace('/^0/', '359', $tel);	
		return $tel;
	}
	
	// check limit
	public function checkLimit() {
		$url = $this->url . 'index.php?sid='.$this->sid.'&check_limit=1';
		$response = wp_remote_get( $url ); 
		return $response;
		
	}
	
	// send email
	public function send(VoicecomMessage $message) {
		
		try {
			
			$message->build();
			
			if (empty($message->to)) { 
// 				throw new \Exception('Cannot send without number');
	            throw CouldNotSendMessage::telNotProvided();				
			}
			
			if (empty($message->message)) { 
// 				throw new \Exception('Cannot send without content');
	            throw CouldNotSendMessage::contentNotProvided();				
			}
			
			$message_processed = Bulglish::toLatin( $this->prefix . $message->message );
			
			if (mb_strlen($message_processed) > 160) {
// 				throw new \Exception('Max length');
	            throw CouldNotSendMessage::maxLengthExceeded();
	        }
			
			$args = [
				'sid' => $this->sid,
				'encoding' => $this->encoding,
				'id' => $message->id.'_'.time(),
				'msisdn' => $this->processTel($message->to),
				'text' => $message_processed,
			];
			
			$query = http_build_query($args);
							
			if($this->log) {
				Log::channel($this->log_channel)->info('Voicecom SMS: '.$query);
			}
			
			if($this->send) {
			
				$response = $this->client->request('GET', '?'.$query );
				$result = (string) $response->getBody();
				
				if($this->log) {
					Log::channel($this->log_channel)->info('Voicecom SMS response: '.$result);
				}
				
	            if (strpos($result, 'SEND_OK') === false) {
	                throw new \Exception($result);
	            }
		
			}
			
		} catch(\Exception $e) {
			
			Log::channel($this->log_channel)->info('Could not send Voicecom SMS ('.$e->getMessage().')');
			
		}
		
	}
}
