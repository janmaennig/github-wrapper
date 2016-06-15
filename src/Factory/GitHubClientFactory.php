<?php

namespace JanMaennig\GitHubWrapper\Service;

use JanMaennig\GitHubWrapper\Configuration\CredentialConfiguration;

/**
 * Class ClientService
 *
 * @package JanMaennig\GitHubWrapper\Service
 */
class GitHubClientFactory
{
    /**
     * @param CredentialConfiguration $credentialConfiguration
     * @return \GitHubClient
     */
    public static function createByCredentialConfiguration(CredentialConfiguration $credentialConfiguration)
    {
        $client = new \GitHubClient();
        $client->setCredentials(
            $credentialConfiguration->getUsername(),
            $credentialConfiguration->getPassword()
        );

        return $client;
    }
}
