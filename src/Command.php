<?php

namespace DiffSniffer\PullRequest;

use DiffSniffer\Changeset as ChangesetInterface;
use DiffSniffer\Command as CommandInterface;
use DiffSniffer\Command\Exception\BadUsage;
use Github\Client;

/**
 * Pull request validation command
 */
class Command implements CommandInterface
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     */
    public function getName() : string
    {
        return 'Diff Sniffer For Pull Requests';
    }

    /**
     * {@inheritDoc}
     */
    public function getPackageName() : string
    {
        return 'morozov/diff-sniffer-pull-request';
    }

    /**
     * {@inheritDoc}
     */
    public function getUsage(string $programName) : string
    {
        return <<<USG
Usage: $programName user repo pull [option]
Validate pull request correspondence to the coding standards

USG;
    }

    /**
     * {@inheritDoc}
     */
    public function createChangeSet(array &$args) : ChangesetInterface
    {
        if (count($args) < 3) {
            throw new BadUsage();
        }

        list($user, $repo, $pull) = array_splice($args, 0, 3);

        return new Changeset($this->client, $user, $repo, $pull);
    }
}
