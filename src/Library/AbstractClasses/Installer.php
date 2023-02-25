<?php

namespace Library\AbstractClasses;

use Composer\Script\Event;
use Library\Messages;

abstract class Installer
{

    public static function start(Event $event)
    {
        self::movePayloads($event);
        self::installDevDependencies($event);
        self::installNodeDevDependencies($event);
    }

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

    private static function movePayloads(Event $event)
    {
        foreach (static::$payloads as $source => $target) {
            if (!is_int($source)) {
                $sourceFileName = $source;
            } else {
                $sourceFileName = $target;
            }

            $target = ltrim($target, '/');
            $targetFileName = "../{$target}";

            if (file_exists($targetFileName) && getenv('OVERWRITE') != true) {
                $message = "{$target} exists, not overwriting.";
                $formattedMessage = Messages::getComment($message);
                Messages::display($event, $formattedMessage);

                $message = 'Set OVERWRITE environment variable to force.';
                $formattedMessage = Messages::getInfo($message);
                Messages::display($event, $formattedMessage);

                continue;
            }

            rename($sourceFileName, $targetFileName);
        }
    }

    private static function installDevDependencies(Event $event)
    {
        $message = 'Installing Composer development dependencies.';
        $formattedMessage = Messages::getInfo($message);
        Messages::display($event, $formattedMessage);



        $composerParameters = implode(' ', static::$packages);

        $workDir = getcwd() . '/../';
        $command = "composer --dev require {$composerParameters} -d {$workDir}";
        exec($command);
    }

    private static function installNodeDevDependencies(Event $event)
    {
        $message = 'Installing Node development dependencies.';
        $formattedMessage = Messages::getInfo($message);
        Messages::display($event, $formattedMessage);



        $npmPackageParameters = implode(' ', static::$nodePackages);
        $workDir = getcwd() . '/../';

        $command = "npm install --prefix {$workDir} --save-dev {$npmPackageParameters}";
        exec($command);
    }
}
