<?php

namespace LeanTesting\API\Client\Entity\Project;

use LeanTesting\API\Client\BaseClass\Entity;

use LeanTesting\API\Client\Handler\Project\ProjectSectionsHandler;
use LeanTesting\API\Client\Handler\Project\ProjectTestCasesHandler;
use LeanTesting\API\Client\Handler\Project\ProjectTestRunsHandler;
use LeanTesting\API\Client\Handler\Project\ProjectVersionsHandler;
use LeanTesting\API\Client\Handler\Project\ProjectUsersHandler;

use LeanTesting\API\Client\Handler\Project\ProjectBugTypeSchemeHandler;
use LeanTesting\API\Client\Handler\Project\ProjectBugStatusSchemeHandler;
use LeanTesting\API\Client\Handler\Project\ProjectBugSeveritySchemeHandler;
use LeanTesting\API\Client\Handler\Project\ProjectBugReproducibilitySchemeHandler;
use LeanTesting\API\Client\Handler\Project\ProjectBugPrioritySchemeHandler;

use LeanTesting\API\Client\Handler\Project\ProjectBugsHandler;
use LeanTesting\API\Client\Handler\Project\ProjectWebhooksHandler;

class Project extends Entity
{
    public $sections;
    public $versions;
    public $users;

    public $bugTypeScheme;
    public $bugStatusScheme;
    public $bugSeverityScheme;
    public $bugReproducibilityScheme;
    public $bugPriorityScheme;

    public $bugs;

    /**
     * Project constructor.
     * @param \LeanTesting\API\Client\Client $origin
     * @param \mixed[] $data
     */
    public function __construct($origin, $data)
    {
        parent::__construct($origin, $data);

        $this->sections  = new ProjectSectionsHandler($origin, $data['id']);
        $this->versions  = new ProjectVersionsHandler($origin, $data['id']);
        $this->users     = new ProjectUsersHandler($origin, $data['id']);
        $this->testRuns  = new ProjectTestRunsHandler($origin, $data['id']);
        $this->testCases = new ProjectTestCasesHandler($origin, $data['id']);
        $this->webhooks  = new ProjectWebhooksHandler($origin, $data['id']);

        $this->bugTypeScheme            = new ProjectBugTypeSchemeHandler($origin, $data['id']);
        $this->bugStatusScheme          = new ProjectBugStatusSchemeHandler($origin, $data['id']);
        $this->bugSeverityScheme        = new ProjectBugSeveritySchemeHandler($origin, $data['id']);
        $this->bugReproducibilityScheme = new ProjectBugReproducibilitySchemeHandler($origin, $data['id']);
        $this->bugPriorityScheme        = new ProjectBugPrioritySchemeHandler($origin, $data['id']);

        $this->bugs = new ProjectBugsHandler($origin, $data['id']);
    }
}
