<?php

namespace LeanTesting\API\Client\Handler\Project;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;
use LeanTesting\API\Client\Entity\Project\ProjectTestCase;

class ProjectTestCasesHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Project\\ProjectTestCase';

    protected $project_id;

    public function __construct($origin, $project_id)
    {
        parent::__construct($origin);

        $this->project_id = $project_id;
    }

    /**
     * @param int $id
     * @return ProjectTestCase
     */
    public function find($id)
    {
        parent::find($id);

        $req = new APIRequest($this->origin, sprintf('/v1/projects/%s/test-cases/%s', $this->project_id, $id), 'GET');
        return new ProjectTestCase($this->origin, $req->exec());
    }

    /**
     * @param array $filters
     * @return EntityList
     */
    public function all($filters = [])
    {
        parent::all($filters);

        $request = new APIRequest(
            $this->origin,
            '/v1/projects/' . $this->project_id . '/test-cases',
            'GET'
        );
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }
}
