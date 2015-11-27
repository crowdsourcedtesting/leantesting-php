<?php

namespace LeanTesting\API\Client\Entity\Bug;

use LeanTesting\API\Client\BaseClass\Entity;

class BugComment extends Entity
{
    public function __construct($origin, $data) {
        parent::__construct($origin, $data);
    }
}
