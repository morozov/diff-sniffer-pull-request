<?php

/**
 * Github pull request validation script
 *
 * PHP version 5
 *
 * @category  DiffSniffer
 * @package   DiffSniffer
 * @author    Sergei Morozov <morozov@tut.by>
 * @copyright 2017 Sergei Morozov
 * @license   http://mit-license.org/ MIT Licence
 * @link      http://github.com/morozov/diff-sniffer-pull-request
 */

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo 'You must set up the project dependencies, run the following commands:'
        . PHP_EOL . 'curl -sS https://getcomposer.org/installer | php'
        . PHP_EOL . 'php composer.phar install'
        . PHP_EOL;
    exit(2);
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/squizlabs/php_codesniffer/autoload.php';

if ($_SERVER['argc'] > 1 && $_SERVER['argv'][1] == '--version') {
    printf(
        'Diff Sniffer For Pull Requests version %s' . PHP_EOL,
        (new \SebastianBergmann\Version('3.0', dirname(__DIR__)))->getVersion()
    );
    exit;
}

$client = new Github\Client();

$config = new DiffSniffer\Config();

if ($config->isDefined()) {
    if ($_SERVER['argc'] < 4) {
        throw new \InvalidArgumentException(
            'Usage: ' . $_SERVER['argv'][0] . ' user repo pull <code sniffer arguments>'
        );
    }

    $self = array_shift($_SERVER['argv']);
    $user = array_shift($_SERVER['argv']);
    $repo = array_shift($_SERVER['argv']);
    $pull = array_shift($_SERVER['argv']);
    array_unshift($_SERVER['argv'], $self);
    $_SERVER['argc'] -= 3;

    return DiffSniffer\run($client, $config, $user, $repo, $pull);
} else {
    DiffSniffer\collectCredentials($client, $config, STDIN, STDOUT);
    return 0;
}
