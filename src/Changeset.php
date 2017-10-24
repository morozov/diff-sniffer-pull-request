<?php

namespace DiffSniffer\PullRequest;

use DiffSniffer\Changeset as ChangesetInterface;
use Github\Client;

/**
 * Changeset that represents pull request on GitHub
 */
class Changeset implements ChangesetInterface
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
     * Map of file names to their SHA1 checksum
     *
     * @var array<string,string>
     */
    private $sha = array();

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
     * {@inheritDoc}
     */
    public function getDiff() : string
    {
        return $this->getSelf('diff');
    }

    /**
     * {@inheritDoc}
     */
    public function getContents(string $path) : string
    {
        return $this->client->api('repo')
            ->contents()
            ->configure('raw')
            ->show(
                $this->user,
                $this->repo,
                $path,
                $this->getSha()
            );
    }

    /**
     * Returns the SHA checksum of the pull request HEAD commit
     *
     * @return string
     */
    private function getSha() : string
    {
        if (!$this->sha) {
            $this->sha = $this->getSelf('json')['head']['sha'];
        }

        return $this->sha;
    }

    /**
     * Returns the representation of the pull request as the given media type
     *
     * @param string $type
     * @return mixed
     *
     * @link https://developer.github.com/v3/media/
     */
    private function getSelf(string $type)
    {
        return $this->client->api('pull_request')
            ->configure($type)
            ->show(
                $this->user,
                $this->repo,
                $this->pull
            );
    }
}
