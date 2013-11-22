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

$config = new DiffSniffer\Config();
$config = $config->getParams();

if ($_SERVER['argc'] < 4) {
    throw new \InvalidArgumentException(
        'Usage: ' . $_SERVER['argv'][0] . ' user repo pull <code sniffer arguments>'
    );
}

$client = new Github\Client(
    new Github\HttpClient\CachedHttpClient(
        array(
            'cache_dir' => '/tmp/github-api-cache',
        )
    )
);

$client->authenticate($config['token'], null, Github\Client::AUTH_URL_TOKEN);

$arguments = $_SERVER['argv'];
array_shift($arguments);

$changeset = new \DiffSniffer\Changeset\PullRequest(
    $client,
    array_shift($arguments),
    array_shift($arguments),
    array_shift($arguments)
);

$runner = new \DiffSniffer\Runner();
return $runner->run($changeset, $arguments);
