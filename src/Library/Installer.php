<?php

namespace Library;

use Composer\Installer\PackageEvent;
use Composer\Script\Event;
use Library\Messages;

class Installer
{
    public static function start(Event $event)
    {
        self::movePayloads($event);
        self::installDevDependencies();
    }

    private static function movePayloads(Event $event)
    {
        $payloads = [
            'vscode/.vscode'                     => '.vscode',
            'standards/javascript/.eslintrc.js'  => '.eslintrc.js',
            'standards/sass/stylelint.config.js' => 'stylelint.config.js',
            'standards/php'                      => 'php-coding-standards',
            'editor/.gitattributes'              => '.gitattributes',
            'editor/.editorconfig'               => '.editorconfig'
        ];

        foreach ($payloads as $source => $target) {
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

    private static function installDevDependencies()
    {

        $packages = [
            'squizlabs/php_codesniffer',
            'dealerdirect/phpcodesniffer-composer-installer',
            'sirbrillig/phpcs-variable-analysis',
        ];

        $composerParameters = implode(' ', $packages);

        $workDir = getcwd() . '/../';
        $command = "composer --dev require {$composerParameters} -d {$workDir}";
        exec($command);
    }

    private static function installNodeDevDependencies()
    {
        $nodePackages = [
            'eslint',
            'eslint-plugin-import',
            'eslint-plugin-n',
            'eslint-plugin-promise',
            'eslint-config-standard',
            'eslint-plugin-vue',
            '@html-eslint/parser',
            '@html-eslint/eslint-plugin',
            'stylelint',
            'stylelint-config-standard',
            'postcss@8',
            'postcss-scss'
        ];

        $npmPackageParameters = implode(' ', $nodePackages);

        $workDir = getcwd() . '/../';
        chdir($workDir);

        $command = "npm install --save-dev {$npmPackageParameters}";
        exec($command);
    }
}
