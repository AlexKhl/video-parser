<?php


namespace Artec3D;


class Logs
{
    /**
     * @var array
     */
    public static $messages = [];

    /**
     * @return array
     */
    public static function getMessages()
    {
        return self::$messages;
    }

    /**
     * @param string $message
     */
    public static function setMessage($message)
    {
        self::$messages[] = $message;
    }
}