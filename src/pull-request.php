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
require_once __DIR__ . '/../vendor/autoload.php';

$client = new Github\Client(
    new Github\HttpClient\CachedHttpClient(
        array(
            'cache_dir' => '/tmp/github-api-cache',
        )
    )
);

$config = new DiffSniffer\Config();

if ($config->isDefined()) {
    return DiffSniffer\run($client, $config, $_SERVER['argv']);
} else {
    DiffSniffer\collectCredentials($client, $config, STDIN, STDOUT);
    return 0;
}
