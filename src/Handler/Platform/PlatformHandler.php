<?php

namespace LeanTesting\API\Client\Handler\Platform;

use LeanTesting\API\Client\Client;

use LeanTesting\API\Client\BaseClass\EntityHandler;

class PlatformHandler extends EntityHandler
{
    public $types;
    public $devices;
    public $os;
    public $browsers;

    public function __construct(Client $origin) {
        parent::__construct($origin);

        $this->types    = new PlatformTypesHandler($origin);
        $this->devices  = new PlatformDevicesHandler($origin);
        $this->os       = new PlatformOSHandler($origin);
        $this->browsers = new PlatformBrowsersHandler($origin);
    }
}