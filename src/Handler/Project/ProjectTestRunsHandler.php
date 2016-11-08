<?php
namespace LeanTesting\API\Client\Handler\Project;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;
use LeanTesting\API\Client\Client;
use LeanTesting\API\Client\Entity\Project\ProjectTestRun;

class ProjectTestRunsHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Project\\ProjectTestRun';

    protected $project_id;

    public function __construct($origin, $project_id)
    {
        parent::__construct($origin);

        $this->project_id = $project_id;
    }

    /**
     * @param array $fields
     * @return ProjectTestRun
     */
    public function create($fields)
    {
        parent::create($fields);

        $supports = [
            'name'       => Client::REQUIRED_PARAM,
            'case_id'    => Client::REQUIRED_PARAM,
            'version_id' => Client::REQUIRED_PARAM,
            'creator_id' => Client::OPTIONAL_PARAM,
            'platform'   => Client::OPTIONAL_PARAM,
        ];

        if ($this->enforce($fields, $supports)) {
            $endpoint = sprintf('/v1/projects/%s/test-runs', $this->project_id);
            $req      = new APIRequest($this->origin, $endpoint, 'POST', ['params' => $fields]);
            return new ProjectTestRun($this->origin, $req->exec());
        }
    }

    /**
     * @param int $id
     * @return ProjectTestRun
     */
    public function find($id)
    {
        parent::find($id);

        $req = new APIRequest($this->origin, sprintf('/v1/projects/%s/test-runs/%s', $this->project_id, $id), 'GET');
        return new ProjectTestRun($this->origin, $req->exec());
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
            '/v1/projects/' . $this->project_id . '/test-runs',
            'GET'
        );
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }
}
