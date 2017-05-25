<?php

namespace DiffSniffer\Tests;

use DiffSniffer\Changeset\PullRequest;
use Github\Client;
use Github\HttpClient\HttpClient;
use PHPUnit\Framework\TestCase;

class PullRequestTest extends TestCase
{
    /**
     * @var PullRequest
     */
    private $changeSet;

    protected function setUp()
    {
        parent::setUp();

        $this->changeSet = new PullRequest(
            new Client(),
            'composer',
            'composer',
            1
        );
    }

    /**
     * @test
     */
    public function getDiff()
    {
        $this->assertStringEqualsFile(
            __DIR__ . '/fixtures/1.diff.txt',
            $this->changeSet->getDiff()
        );
    }

    /**
     * @test
     */
    public function getContents()
    {
        $this->assertStringEqualsFile(
            __DIR__ . '/fixtures/workspace/bootstrap.php.txt',
            $this->changeSet->getContents('tests/bootstrap.php')
        );
    }
}
