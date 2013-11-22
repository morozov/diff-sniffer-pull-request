<?php

/**
 * Github authentication script
 *
 * PHP version 5
 *
 * @category  DiffSniffer
 * @package   DiffSniffer
 * @author    Sergei Morozov <morozov@tut.by>
 * @copyright 2012 Sergei Morozov
 * @license   http://mit-license.org/ MIT Licence
 * @link      http://github.com/morozov/diff-sniffer
 */
require_once __DIR__ . '/../vendor/autoload.php';

echo 'Username: ';
$username = trim(fgets(STDIN));

echo 'Password: ';
$password = trim(fgets(STDIN));

$client = new Github\Client();
$client->authenticate($username, $password, Github\Client::AUTH_HTTP_PASSWORD);

/** @var \Github\Api\Authorizations $api */
$api = $client->api('authorizations');

$info = $api->create(
    array(
        'note' => 'DiffSniffer',
        'scopes' => 'repo',
    )
);

$config = new DiffSniffer\Config();
$config->setParams($info);
