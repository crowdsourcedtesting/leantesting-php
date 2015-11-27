<?php

namespace LeanTesting\API\Client\Handler\Platform;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;

use LeanTesting\API\Client\Entity\Platform\PlatformBrowser;

class PlatformBrowsersHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Platform\\PlatformBrowser';

    public function all($filters = []) {
        parent::all($filters);

        $filters = array_merge(['include' => 'versions'], $filters);

        $request = new APIRequest($this->origin, '/v1/platform/browsers', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }

    public function find($id) {
        parent::find($id);

        $req = new APIRequest($this->origin, '/v1/platform/browsers/' . $id, 'GET', ['params' => ['include' => 'versions']]);
        return new PlatformBrowser($this->origin, $req->exec());
    }
}
