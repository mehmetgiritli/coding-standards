<?php

namespace Library\AbstractClasses\OS;

use Library\AbstractClasses\Installer;

class LinuxInstaller extends Installer
{
    public static function cleanUp()
    {
        $workDirectory = getcwd();

        if (PHP_OS_FAMILY === 'Windows') {
            $command = sprintf('rd /s /q %s', $workDirectory);
        } else {
            $command = sprintf('rm -rf %s', $workDirectory);
        }

        exec($command);
    }
}
