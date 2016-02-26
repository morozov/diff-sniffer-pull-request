<?php

/**
 * Changeset that represents pull request on GitHub
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
namespace DiffSniffer\Changeset;

use DiffSniffer\Changeset;
use Github\Client;
use Github\Exception\RuntimeException;

/**
 * Changeset that represents pull request on GitHub
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
class PullRequest implements Changeset
{
    /**
     * GitHub API client
     *
     * @var Client
     */
    protected $client;

    /**
     * GitHub user name
     *
     * @var string
     */
    protected $user;

    /**
     * GitHub repository
     *
     * @var string
     */
    protected $repo;

    /**
     * GitHub pull request ID
     *
     * @var int
     */
    protected $pull;

    /**
     * Constructor
     *
     * @param Client $client GitHub API client
     * @param string $user   GitHub user name
     * @param string $repo   GitHub repository
     * @param string $pull   GitHub pull request ID
     */
    public function __construct(Client $client, $user, $repo, $pull)
    {
        $this->client = $client;
        $this->user = $user;
        $this->repo = $repo;
        $this->pull = $pull;
    }

    /**
     * Returns diff of the changeset
     *
     * @return string
     * @throws Exception
     */
    public function getDiff()
    {
        /** @var \Github\Api\PullRequest $api */
        $api = $this->client->api('pull_request');
        $this->client->setHeaders(array(
            'Accept' => sprintf(
                'application/vnd.github.%s.diff',
                $this->client->getOption('api_version')
            ),
        ));

        $diff = $api->show($this->user, $this->repo, $this->pull);

        return $diff;
    }

    /**
     * Exports the changed files into specified directory
     *
     * @param string $dir Target directory
     *
     * @return void
     * @throws Exception
     */
    public function export($dir)
    {
        /** @var \Github\Api\PullRequest $api */
        $api = $this->client->api('pull_request');
        $files = $api->files($this->user, $this->repo, $this->pull);

        /** @var \Github\Api\GitData $api */
        $api = $this->client->api('git_data');
        $api = $api->blobs();
        /** @var \Github\Api\GitData\Blobs $api */
        $api->configure('raw');
        foreach ($files as $file) {
            try {
                $contents = $this->getContents($file['sha']);
            } catch (RuntimeException $e) {
                if ($e->getCode() === 404) {
                    // this is probably a submodule reference
                    // :TODO: need a better solution
                    continue;
                }
            }

            $path = $dir . '/' . $file['filename'];
            $dirName = dirname($path);
            if (!file_exists($dirName)) {
                mkdir(dirname($path), 0777, true);
            }
            file_put_contents($path, $contents);
        }
    }

    /**
     * Temporary workaround of the GitHub client bug
     *
     * @param string $sha
     * @return string
     */
    protected function getContents($sha)
    {
        $path = 'repos/' . rawurlencode($this->user)
            . '/' . rawurlencode($this->repo)
            . '/git/blobs/' . rawurlencode($sha);
        $response = $this->client->getHttpClient()->get($path);
        $contents = $response->getBody(true);

        return $contents;
    }
}
