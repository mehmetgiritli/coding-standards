<?php

namespace Library;

use Composer\Installer\PackageEvent;
use Composer\Script\Event;

class Installer
{
    public static function start(Event $event)
    {
        self::movePayloads();
        self::installDevDependencies();
    }

    private static function movePayloads()
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

            if (file_exists($targetFileName) && getenv('OVERWRITE') !== true) {
                echo "{$targetFileName} exists, not overwriting.\n";
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

        chdir('..');
        $composerParameters = implode(' ', $packages);

        exec("composer require --dev {$composerParameters}");
    }

    /*
        "npm install --save-dev eslint eslint-plugin-import eslint-plugin-n eslint-plugin-promise eslint-config-standard eslint-plugin-vue @html-eslint/parser @html-eslint/eslint-plugin",
        "npm install --save-dev stylelint stylelint-config-standard postcss@8 postcss-scss",
    */
}
