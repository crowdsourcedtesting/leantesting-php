<?php

namespace LeanTesting\API\Client\Entity\Platform;

use LeanTesting\API\Client\BaseClass\Entity;

use LeanTesting\API\Client\Handler\Platform\PlatformTypeDevicesHandler;

class PlatformType extends Entity
{
    public $devices;

    public function __construct($origin, $data) {
        parent::__construct($origin, $data);

        $this->devices = new PlatformTypeDevicesHandler($origin, $data['id']);
    }
}
