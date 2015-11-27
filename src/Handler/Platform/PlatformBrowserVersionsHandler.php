<?php

namespace LeanTesting\API\Client\Handler\Platform;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;

class PlatformBrowserVersionsHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Platform\\PlatformBrowserVersion';

    protected $browser_id;

    public function __construct($origin, $browser_id) {
        parent::__construct($origin);

        $this->browser_id = $browser_id;
    }

    public function all($filters = []) {
        parent::all($filters);

        $request = new APIRequest($this->origin, '/v1/platform/browsers/' . $this->browser_id . '/versions', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }
}
