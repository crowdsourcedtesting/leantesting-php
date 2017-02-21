<?php

namespace LeanTesting\API\Client\Entity\Project;

use LeanTesting\API\Client\BaseClass\Entity;

class ProjectWebhook extends Entity
{
    public function __construct($origin, $data)
    {
        parent::__construct($origin, $data);
    }
}
