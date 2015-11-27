<?php

namespace LeanTesting\API\Client\Entity\Platform;

use LeanTesting\API\Client\BaseClass\Entity;

use LeanTesting\API\Client\Handler\Platform\PlatformOSVersionsHandler;

class PlatformOS extends Entity
{
    public $versions;

    public function __construct($origin, $data) {
        parent::__construct($origin, $data);

        $this->versions = new PlatformOSVersionsHandler($origin, $data['id']);
    }
}
