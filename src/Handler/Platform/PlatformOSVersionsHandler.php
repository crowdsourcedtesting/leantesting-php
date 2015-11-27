<?php

namespace LeanTesting\API\Client\Handler\Platform;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;

class PlatformOSVersionsHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Platform\\PlatformOSVersion';

    protected $os_id;

    public function __construct($origin, $os_id) {
        parent::__construct($origin);

        $this->os_id = $os_id;
    }

    public function all($filters = []) {
        parent::all($filters);

        $request = new APIRequest($this->origin, '/v1/platform/os/' . $this->os_id . '/versions', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }
}
