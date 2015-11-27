<?php

namespace LeanTesting\API\Client\Handler\User;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;

class UserOrganizationsHandler extends EntityHandler
{
	private $return_class = 'LeanTesting\\API\\Client\\Entity\\User\\UserOrganization';

    public function all($filters = []) {
        parent::all($filters);

        $request = new APIRequest($this->origin, '/v1/me/organizations', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }
}
