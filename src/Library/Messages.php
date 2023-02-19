<?php

namespace Library;

use Composer\Script\Event;

class Messages
{
    public static function getComment($message)
    {
        return "<comment>{$message}</comment>";
    }

    public static function getError($message)
    {
        return "<error>{$message}</error>";
    }

    public static function getInfo($message)
    {
        return "<info>{$message}</info>";
    }

    public static function display(Event $event, $message)
    {
        $event->getIO()->write($message);
    }
}
