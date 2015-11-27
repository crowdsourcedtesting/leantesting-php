<?php

namespace LeanTesting\API\Client\Entity\Platform;

use LeanTesting\API\Client\BaseClass\Entity;

use LeanTesting\API\Client\Handler\Platform\PlatformBrowserVersionsHandler;

class PlatformBrowser extends Entity
{
    public $versions;

    public function __construct($origin, $data) {
        parent::__construct($origin, $data);

        $this->versions = new PlatformBrowserVersionsHandler($origin, $data['id']);
    }
}
