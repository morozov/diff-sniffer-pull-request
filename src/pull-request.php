<?php

/**
 * Github pull request validation script
 *
 * PHP version 5
 *
 * @category  DiffSniffer
 * @package   DiffSniffer
 * @author    Sergei Morozov <morozov@tut.by>
 * @copyright 2014 Sergei Morozov
 * @license   http://mit-license.org/ MIT Licence
 * @link      http://github.com/morozov/diff-sniffer-pull-request
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

if ($_SERVER['argc'] > 1 && $_SERVER['argv'][1] == '--version') {
    echo 'Diff Sniffer For Pull Requests version 2.3.2' . PHP_EOL;
    $cli = new PHP_CodeSniffer_CLI();
    $cli->processLongArgument('version', null, null);
    exit;
}

$client = new Github\Client(
    new Github\HttpClient\CachedHttpClient(
        array(
            'cache_dir' => sys_get_temp_dir() . '/github-api-cache',
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

    $arguments = $_SERVER['argv'];
    array_shift($arguments);

    return DiffSniffer\run($client, $config, $arguments);
} else {
    DiffSniffer\collectCredentials($client, $config, STDIN, STDOUT);
    return 0;
}
