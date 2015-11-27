<?php

namespace LeanTesting\API\Client\Entity\Project;

use LeanTesting\API\Client\BaseClass\Entity;

class ProjectVersion extends Entity
{
    public function __construct($origin, $data) {
        parent::__construct($origin, $data);
    }
}
