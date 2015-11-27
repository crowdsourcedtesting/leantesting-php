<?php

namespace LeanTesting\API\Client\Handler\Platform;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;

use LeanTesting\API\Client\Entity\Platform\PlatformType;

class PlatformTypesHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Platform\\PlatformType';

    public function all($filters = []) {
        parent::all($filters);

        $request = new APIRequest($this->origin, '/v1/platform/types', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }

    public function find($id) {
        parent::find($id);

        $req = new APIRequest($this->origin, '/v1/platform/types/' . $id, 'GET');
        return new PlatformType($this->origin, $req->exec());
    }
}
