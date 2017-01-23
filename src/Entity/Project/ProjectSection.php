<?php

namespace LeanTesting\API\Client\Entity\Project;

use LeanTesting\API\Client\BaseClass\Entity;
use LeanTesting\API\Client\Handler\Project\ProjectTestCasesHandler;

class ProjectSection extends Entity
{
    /**
     * @var ProjectTestCasesHandler
     */
    public $tests;

    public function __construct($origin, $data)
    {
        parent::__construct($origin, $data);

        $this->tests  = new ProjectTestCasesHandler($origin, $data['id']);
    }
}
