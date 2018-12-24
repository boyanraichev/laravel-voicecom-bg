<?php
namespace Boyo\Voicecom\Exceptions;

class CouldNotSendMessage extends \Exception
{
    /**
     * Thrown when there is no phone provided.
     *
     * @return static
     */
    public static function telNotProvided()
    {
        return new static('Phone number was missing.');
    }
    
    /**
     * Thrown when there is content provided.
     *
     * @return static
     */
    public static function contentNotProvided()
    {
        return new static('Content was not provided.');
    }
    
    /**
     * Thrown when there is no Gammu Api auth key provided.
     *
     * @return static
     */
    public static function senderIdNotProvided()
    {
        return new static('Missing sender ID in config/services/voicecom.');
    }
    
    /**
     * Thrown when there is no Gammu Api auth key provided.
     *
     * @return static
     */
    public static function maxLengthExceeded()
    {
        return new static('The 160 characters max length has been exceeded.');
    }
    
}