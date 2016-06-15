<?php

namespace JanMaennig\GitHubWrapper\Configuration;

/**
 * Class CredentialConfiguration
 *
 * @package JanMaennig\GitHubWrapper\Configuration
 */
class CredentialConfiguration
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}
