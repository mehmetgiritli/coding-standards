<?php

namespace Library;

function getParentForOS()
{
    return 'Library\AbstractClasses\OS\\' . PHP_OS_FAMILY . 'Installer';
}

class_alias(getParentForOS(), 'Library\InstallerForOS');

class FullInstaller extends InstallerForOS
{
    /** @var $payloads Files we would like to move to project's root. */
    protected static $payloads = [
        'vscode/.vscode'                     => '.vscode',
        'standards/javascript/.eslintrc.js'  => '.eslintrc.js',
        'standards/sass/stylelint.config.js' => 'stylelint.config.js',
        'standards/php'                      => 'php-coding-standards',
        'editor/.gitattributes'              => '.gitattributes',
        'editor/.editorconfig'               => '.editorconfig'
    ];

    /** @var $packages Composer development dependencies required. */
    protected static $packages = [
        'squizlabs/php_codesniffer',
        'dealerdirect/phpcodesniffer-composer-installer',
        'sirbrillig/phpcs-variable-analysis',
    ];

    /** @var $nodePackages NPM development packages needed. */
    protected static $nodePackages = [
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
}
