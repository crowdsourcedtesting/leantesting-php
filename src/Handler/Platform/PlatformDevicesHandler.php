<?php

namespace LeanTesting\API\Client\Handler\Platform;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;

use LeanTesting\API\Client\Entity\Platform\PlatformDevice;

class PlatformDevicesHandler extends EntityHandler
{
    public function find($id) {
        parent::find($id);

        $req = new APIRequest($this->origin, '/v1/platform/devices/' . $id, 'GET');
        return new PlatformDevice($this->origin, $req->exec());
    }
}
