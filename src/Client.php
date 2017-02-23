<?php

namespace LeanTesting\API\Client;

use LeanTesting\API\Client\Exception\SDKInvalidArgException;
use LeanTesting\API\Client\Handler\Auth\OAuth2Handler;
use LeanTesting\API\Client\Handler\User\UserHandler;
use LeanTesting\API\Client\Handler\Project\ProjectsHandler;
use LeanTesting\API\Client\Handler\Bug\BugsHandler;
use LeanTesting\API\Client\Handler\Attachment\AttachmentsHandler;
use LeanTesting\API\Client\Handler\Platform\PlatformHandler;

/**
 *
 * Lean Testing PHP Client SDK
 *
 * @link https://leantesting.com/en/api-docs Adheres to official API guidelines
 *
 */
final class Client
{
    const OPTIONAL_PARAM = 0;
    const REQUIRED_PARAM = 1;

    protected $access_token = null;

    public $auth;
    public $user;
    public $projects;
    public $bugs;
    public $attachments;
    public $platform;

    /**
     * @var string The base URL for the Lean Testing API.
     */
    public static $api_base_uri = 'https://api.leantesting.com';

    /**
     * @var string The base URL for Lean Testing OAuth endpoints
     */
    public static $oauth_base_uri = 'https://app.leantesting.com';

    public $debug_return = null;

    public function __construct()
    {
        $this->auth        =  new OAuth2Handler($this, self::$oauth_base_uri);
        $this->user        =  new UserHandler($this);
        $this->projects    =  new ProjectsHandler($this);
        $this->bugs        =  new BugsHandler($this);
        $this->attachments =  new AttachmentsHandler($this);
        $this->platform    =  new PlatformHandler($this);
    }

    /**
     *
     * Function to retrieve currently attached token.
     *
     * @return string|bool Returns string of attached token or false if no token is attached.
     *
     */
    public function getCurrentToken()
    {
        if ($this->access_token === null) {
            return false;
        }

        return $this->access_token;
    }

    /**
     *
     * Function to attach new token to SDK Client instance. Token changes are dynamic; all objects/entities originating
     * from an instance which has had its token updated will utilize the new token automatically.
     *
     * @param string $access_token the string of the token to be attached
     *
     * @throws SDKInvalidArgException if provided $access_token param is not a string
     *
     */
    public function attachToken($access_token)
    {
        if (!is_string($access_token)) {
            throw new SDKInvalidArgException('`$access_token` must be a string');
        }

        $this->access_token = $access_token;
    }

}
