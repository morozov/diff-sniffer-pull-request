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

use DiffSniffer\Changeset\PullRequest;
use DiffSniffer\Runner;
use Github\Client;
use SebastianBergmann\Version;

require __DIR__ . '/bootstrap.php';

if ($_SERVER['argc'] > 1 && $_SERVER['argv'][1] == '--version') {
    printf(
        'Diff Sniffer For Pull Requests version %s' . PHP_EOL,
        (new Version('3.0', dirname(__DIR__)))->getVersion()
    );
    exit;
}

if ($_SERVER['argc'] < 4) {
    throw new InvalidArgumentException(
        'Usage: ' . $_SERVER['argv'][0] . ' user repo pull <code sniffer arguments>'
    );
}

$client = new Client();

if (file_exists(__DIR__ . '/../etc/config.php')) {
    $config = require __DIR__ . '/../etc/config.php';
    $client->authenticate($config['token'], null, Client::AUTH_URL_TOKEN);
}

$self = array_shift($_SERVER['argv']);
$user = array_shift($_SERVER['argv']);
$repo = array_shift($_SERVER['argv']);
$pull = array_shift($_SERVER['argv']);
array_unshift($_SERVER['argv'], $self);
$_SERVER['argc'] -= 3;

return (new Runner())->run(
    new PullRequest($client, $user, $repo, $pull)
);
