<?php

namespace LeanTesting\API\Client\Handler\User;

use LeanTesting\API\Client\Client;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;

class UserHandler extends EntityHandler
{
    public $organizations;

    public function __construct(Client $origin)
    {
        parent::__construct($origin);

        $this->organizations = new UserOrganizationsHandler($origin);
    }

    public function getInformation()
    {
        return (new APIRequest($this->origin, '/v1/me', 'GET'))->exec();
    }
}
