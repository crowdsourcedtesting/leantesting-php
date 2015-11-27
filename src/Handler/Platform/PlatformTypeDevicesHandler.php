<?php

namespace LeanTesting\API\Client\Handler\Platform;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;

class PlatformTypeDevicesHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Platform\\PlatformDevice';

    protected $type_id;

    public function __construct($origin, $type_id) {
        parent::__construct($origin);

        $this->type_id = $type_id;
    }

    public function all($filters = []) {
        parent::all($filters);

        $request = new APIRequest($this->origin, '/v1/platform/types/' . $this->type_id . '/devices', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }
}
