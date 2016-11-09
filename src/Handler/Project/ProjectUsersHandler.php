<?php

namespace LeanTesting\API\Client\Handler\Project;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;
use LeanTesting\API\Client\Client;
use LeanTesting\API\Client\Entity\Project\ProjectUser;

class ProjectUsersHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Project\\ProjectUser';

    protected $project_id;

    public function __construct($origin, $project_id)
    {
        parent::__construct($origin);

        $this->project_id = $project_id;
    }

    /**
     * @param array $fields
     * @return ProjectUser
     */
    public function create($fields)
    {
        parent::create($fields);

        $supports = [
            'email'     => Client::REQUIRED_PARAM,
            'role_slug' => Client::REQUIRED_PARAM,
        ];

        if ($this->enforce($fields, $supports)) {
            $endpoint = sprintf('/v1/projects/%s/users', $this->project_id);
            $req      = new APIRequest($this->origin, $endpoint, 'POST', ['params' => $fields]);
            return new ProjectUser($this->origin, $req->exec());
        }
    }


    public function delete($id)
    {
        parent::delete($id);

        $endpoint = sprintf('/v1/projects/%s/users/%s', $this->project_id, $id);
        $req      = new APIRequest($this->origin, $endpoint, 'DELETE');
        return $req->exec();
    }

    public function all($filters = [])
    {
        parent::all($filters);

        $request = new APIRequest($this->origin, '/v1/projects/' . $this->project_id . '/users', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }
}
