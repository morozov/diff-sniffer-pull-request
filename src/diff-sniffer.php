<?php

/**
 * Auxiliary functions
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
namespace DiffSniffer;

use Github\Client;
use DiffSniffer\Changeset\PullRequest as Changeset;

/**
 * Collects GitHub credentials and stores them.
 *
 * @param Client   $client GitHub client
 * @param Config   $config Configuration
 * @param resource $input  Input stream
 * @param resource $output Output stream
 */
function collectCredentials(Client $client, Config $config, $input, $output)
{
    fwrite($output, 'Username: ');
    $username = trim(fgets($input));

    fwrite($output, 'Password: ');
    $password = trim(fgets($input));

    $client->authenticate($username, $password, Client::AUTH_HTTP_PASSWORD);

    /** @var \Github\Api\Authorizations $api */
    $api = $client->api('authorizations');

    $info = $api->create(
        array(
            'note' => 'DiffSniffer',
            'scopes' => 'repo',
        )
    );

    $config->setParams($info);
    fwrite($output, 'Configuration successfully saved.' . PHP_EOL);
}

/**
 * Runs pull request validation
 *
 * @param Client $client    GitHub client
 * @param Config $config    Configuration
 * @param array  $arguments Command line arguments
 *
 * @return int              Exit code
 * @throws \InvalidArgumentException
 */
function run(Client $client, Config $config, array $arguments)
{
    $config = $config->getParams();

    if (count($arguments) < 4) {
        throw new \InvalidArgumentException(
            'Usage: ' . $arguments[0] . ' user repo pull <code sniffer arguments>'
        );
    }

    $client->authenticate($config['token'], null, Client::AUTH_URL_TOKEN);

    array_shift($arguments);

    $changeset = new Changeset(
        $client,
        array_shift($arguments),
        array_shift($arguments),
        array_shift($arguments)
    );

    $runner = new Runner();
    return $runner->run($changeset, $arguments);
}
