<?php

/**
 * Github pull request validation script
 *
 * PHP version 5
 *
 * @category  DiffSniffer
 * @package   DiffSniffer
 * @author    Sergei Morozov <morozov@tut.by>
 * @copyright 2013 Sergei Morozov
 * @license   http://mit-license.org/ MIT Licence
 * @link      http://github.com/morozov/diff-sniffer
 */
$autoload = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($autoload)) {
    echo 'You must set up the project dependencies, run the following commands:'
        . PHP_EOL . 'curl -sS https://getcomposer.org/installer | php'
        . PHP_EOL . 'php composer.phar install'
        . PHP_EOL;
    exit(-1);
}

require $autoload;

$client = new Github\Client(
    new Github\HttpClient\CachedHttpClient(
        array(
            'cache_dir' => '/tmp/github-api-cache',
        )
    )
);

$config = new DiffSniffer\Config();

if ($config->isDefined()) {
    if ($_SERVER['argc'] < 4) {
        throw new \InvalidArgumentException(
            'Usage: ' . $_SERVER['argv'][0] . ' user repo pull <code sniffer arguments>'
        );
    }

    $arguments = DiffSniffer\getCodeSnifferArguments(
        $_SERVER['argv'],
        __DIR__ . '/../config.php'
    );

    return DiffSniffer\run($client, $config, $arguments);
} else {
    DiffSniffer\collectCredentials($client, $config, STDIN, STDOUT);
    return 0;
}
