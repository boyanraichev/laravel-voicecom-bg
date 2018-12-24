<?php
namespace Boyo\Voicecom;

use Illuminate\Support\ServiceProvider;

class VoicecomServiceProvider extends ServiceProvider
{
	
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }
    
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(\Boyo\Voicecom\VoicecomSender::class, function () {
            return new \Boyo\Voicecom\VoicecomSender();
        });
    }
    
}