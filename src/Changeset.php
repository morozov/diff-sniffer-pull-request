<?php declare(strict_types=1);

namespace DiffSniffer\PullRequest;

use DiffSniffer\Changeset as ChangesetInterface;
use Github\Api\PullRequest;
use Github\Api\Repo;
use Github\Client;

/**
 * Changeset that represents pull request on GitHub
 */
final class Changeset implements ChangesetInterface
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
     * SHA checksum of the pull request HEAD commit
     *
     * @var string
     */
    private $sha;

    /**
     * Constructor
     *
     * @param Client $client GitHub API client
     * @param string $user   GitHub user name
     * @param string $repo   GitHub repository
     * @param int    $pull   GitHub pull request ID
     */
    public function __construct(Client $client, string $user, string $repo, int $pull)
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
        /** @var Repo $api */
        $api = $this->client->api('repo');

        return $api
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
     * @return array|string
     *
     * @link https://developer.github.com/v3/media/
     */
    private function getSelf(string $type)
    {
        /** @var PullRequest $api */
        $api = $this->client->api('pull_request');

        return $api
            ->configure($type)
            ->show(
                $this->user,
                $this->repo,
                $this->pull
            );
    }
}
