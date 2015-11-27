<?php

namespace LeanTesting\API\Client\Handler\Project;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;

class ProjectBugSeveritySchemeHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Project\\ProjectBugScheme';

    protected $project_id;

    public function __construct($origin, $project_id) {
        parent::__construct($origin);

        $this->project_id = $project_id;
    }

    public function all($filters = []) {
        parent::all($filters);

        $request = new APIRequest($this->origin, '/v1/projects/' . $this->project_id . '/bug-severity-scheme', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }
}
