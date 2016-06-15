<?php

namespace JanMaennig\GitHubWrapper\Service;

/**
 * Class CommitService
 *
 * @package JanMaennig\GitHubWrapper\Service
 */
class CommitService
{
    const COUNT_COMMITS_PER_PAGE = 30;

    /**
     * @var \GitHubClient
     */
    protected $gitHubClient;

    /**
     * Initialize commit service to get different commit informations
     *
     * @param \GitHubClient $gitHubClient
     */
    public function __construct(\GitHubClient $gitHubClient)
    {
        $this->gitHubClient = $gitHubClient;
    }

    /**
     * @param string $repositoryOwner
     * @param string $repositoryName
     * @param int $page
     *
     * @return array
     */
    public function getCommitsPageByRepositoryOwnerAndName($repositoryOwner, $repositoryName, $page = 1)
    {
        $this->gitHubClient->setPage($page);
        return $this->gitHubClient->repos->commits->listCommitsOnRepository($repositoryOwner, $repositoryName);
    }

    /**
     * @param string $repositoryOwner
     * @param string $repositoryName
     *
     * @return array
     */
    public function getAllCommitsByRepositoryOwnerAndName($repositoryOwner, $repositoryName, $shaFirstCommit)
    {
        $commits = $this->getCommitsPageByRepositoryOwnerAndName($repositoryOwner, $repositoryName);
        $countCommits = $this->determineCommitCount(
            $repositoryOwner,
            $repositoryName,
            $shaFirstCommit,
            $commits[0]->getSha()
        );

        $countPages = (int) ceil($countCommits / self::COUNT_COMMITS_PER_PAGE);

        for ($page = 2; $page <= $countPages; $page++) {
            $commitsPage = $this->getCommitsPageByRepositoryOwnerAndName($repositoryOwner, $repositoryName, $page);
            $commits = array_merge($commits, $commitsPage);
        }

        return $commits;
    }

    /**
     * Get the count of commits by repository name, owner and first commit sha
     *
     * @param string $repositoryOwner
     * @param string $repositoryName
     * @param string $shaFirstCommit
     *
     * @return int
     */
    public function getCountCommitsByRepositoryOwnerAndName($repositoryOwner, $repositoryName, $shaFirstCommit)
    {
        $lastCommits = $this->getLastCommitByRepositoryOwnerAndName($repositoryOwner, $repositoryName);
        $shaLastCommit = $lastCommits->getSha();

        return $this->determineCommitCount($repositoryOwner, $repositoryName, $shaFirstCommit, $shaLastCommit);
    }

    /**
     * @param string $repositoryOwner
     * @param string $repositoryName
     *
     * @return \GitHubCommit
     */
    public function getLastCommitByRepositoryOwnerAndName($repositoryOwner, $repositoryName)
    {
        $commits = $this->getCommitsPageByRepositoryOwnerAndName($repositoryOwner, $repositoryName);

        return $commits[0];
    }

    /**
     * @param string $repositoryOwner
     * @param string $repositoryName
     * @param string $shaFirstCommit
     * @param string $shaLastCommit
     *
     * @return int
     */
    protected function determineCommitCount($repositoryOwner, $repositoryName, $shaFirstCommit, $shaLastCommit)
    {
        $commitStatistic = $this->gitHubClient->repos->commits->compareTwoCommits(
            $repositoryOwner,
            $repositoryName,
            $shaFirstCommit,
            $shaLastCommit
        );

        return intval($commitStatistic->getTotalCommits() + 1);
    }
}
