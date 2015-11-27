<?php

namespace LeanTesting\API\Client\Handler\Platform;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;

use LeanTesting\API\Client\Entity\Platform\PlatformOS;

class PlatformOSHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Platform\\PlatformOS';

    public function all($filters = []) {
        parent::all($filters);

        $filters = array_merge(['include' => 'versions'], $filters);

        $request = new APIRequest($this->origin, '/v1/platform/os', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }

    public function find($id) {
        parent::find($id);

        $req = new APIRequest($this->origin, '/v1/platform/os/' . $id, 'GET', ['params' => ['include' => 'versions']]);
        return new PlatformOS($this->origin, $req->exec());
    }
}
