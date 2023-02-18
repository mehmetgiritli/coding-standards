<?php

namespace Library;

use Composer\Installer\PackageEvent;
use Composer\Script\Event;

class Installer
{
    public function start(PackageEvent $event)
    {
        echo 'Hello';
    }
}
