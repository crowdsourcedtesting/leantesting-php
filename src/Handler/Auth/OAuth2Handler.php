<?php

namespace LeanTesting\API\Client\Handler\Auth;

use LeanTesting\API\Client\Client;

use LeanTesting\API\Client\BaseClass\APIRequest;

use LeanTesting\API\Client\Exception\SDKInvalidArgException;

/**
 *
 * Handler to manage general authentication routines
 *
 * @link leantesting.com/en/api-docs#oauth-flow
 *
 */
class OAuth2Handler
{
    private $origin; // Reference to originating PHPClient instance

    public function __construct(Client $origin)
    {
        $this->origin = $origin;
    }

    /**
     *
     * Function that generates link for user to follow in order to request authorization code
     *
     * @param string $client_id    client ID given at application registration
     * @param string $redirect_uri URL to be redirected to after authorization
     * @param string $scope        (optional) comma-separated list of requested scopes (default: 'read')
     * @param string $state        (optional) random string for MITM attack prevention
     *
     * @throws SDKInvalidArgException if provided $client_id param is not a string
     * @throws SDKInvalidArgException if provided $redirect_uri param is not a string
     * @throws SDKInvalidArgException if provided $scope param is not a string
     * @throws SDKInvalidArgException if provided $state param is not a string
     *
     * @return string returns URL to follow for authorization code request
     *
     */
    public function generateAuthLink($client_id, $redirect_uri, $scope = 'read', $state = null)
    {
        if (!is_string($client_id)) {
            throw new SDKInvalidArgException('`$client_id` must be a string');
        } elseif (!is_string($redirect_uri)) {
            throw new SDKInvalidArgException('`$redirect_uri` must be a string');
        } elseif (!is_string($scope)) {
            throw new SDKInvalidArgException('`$scope` must be a string');
        } elseif ($state != null && !is_string($state)) {
            throw new SDKInvalidArgException('`$state` must be a string');
        }

        $base_url = 'https://app.leantesting.com/login/oauth/authorize';

        $params = [
            'client_id'    => $client_id,
            'redirect_uri' => $redirect_uri,
            'scope'        => $scope
        ];

        if ($state != null) {
            $params['state'] = $state;
        }

        $base_url .= '?' . http_build_query($params);

        return $base_url;
    }

    /**
     *
     * Generates an access token string from the provided authorization code
     *
     * @param string $client_id     client ID given at application registration
     * @param string $client_secret client secret given at application registration
     * @param string $grant_type    oauth specific grant_type value (i.e.: authorization_code)
     * @param string $code          authorization code obtained from the generated auth link
     * @param string $redirect_uri  URL to be redirected to after authorization
     *
     * @throws SDKInvalidArgException if provided $client_id param is not a string
     * @throws SDKInvalidArgException if provided $client_secret param is not a string
     * @throws SDKInvalidArgException if provided $grant_type param is not a string
     * @throws SDKInvalidArgException if provided $code param is not a string
     * @throws SDKInvalidArgException if provided $redirect_uri param is not a string
     *
     * @return string returns obtained access token string
     *
     */
    public function exchangeAuthCode($client_id, $client_secret, $grant_type, $code, $redirect_uri)
    {
        if (!is_string($client_id)) {
            throw new SDKInvalidArgException('`$client_id` must be a string');
        } elseif (!is_string($client_secret)) {
            throw new SDKInvalidArgException('`$client_secret` must be a string');
        } elseif (!is_string($grant_type)) {
            throw new SDKInvalidArgException('`$grant_type` must be a string');
        } elseif (!is_string($code)) {
            throw new SDKInvalidArgException('`$code` must be a string');
        } elseif (!is_string($redirect_uri)) {
            throw new SDKInvalidArgException('`$redirect_uri` must be a string');
        }

        $params = [
            'grant_type'    => $grant_type,
            'client_id'     => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri'  => $redirect_uri,
            'code'          => $code
        ];

        $req = new APIRequest(
            $this->origin,
            '/login/oauth/access_token',
            'POST',
            [
                'base_uri' => 'https://app.leantesting.com',
                'params'   => $params
            ]
        );

        $resp = $req->exec();
        return $resp['access_token'];
    }
}
