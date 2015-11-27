<?php

namespace LeanTesting\API\Client\Handler\Project;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;

use LeanTesting\API\Client\Entity\Project\Project;

class ProjectsHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Project\\Project';

    public function create($fields) {
        parent::create($fields);

        $supports = [
            'name'            => REQUIRED,
            'organization_id' => OPTIONAL
        ];

        if ($this->enforce($fields, $supports)) {
            $req = new APIRequest($this->origin, '/v1/projects', 'POST', ['params' => $fields]);
            return new Project($this->origin, $req->exec());
        }
    }

    public function all($filters = []) {
        parent::all($filters);

        $request = new APIRequest($this->origin, '/v1/projects', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }

    public function allArchived($filters = []) {
        parent::all($filters);

        $request = new APIRequest($this->origin, '/v1/projects/archived', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }

    public function find($id) {
        parent::find($id);

        $req = new APIRequest($this->origin, '/v1/projects/' . $id, 'GET');
        return new Project($this->origin, $req->exec());
    }
}
