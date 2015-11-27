<?php

namespace LeanTesting\API\Client\Handler\Project;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;

use LeanTesting\API\Client\Entity\Project\ProjectVersion;

class ProjectVersionsHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Project\\ProjectVersion';

    protected $project_id;

    public function __construct($origin, $project_id) {
        parent::__construct($origin);

        $this->project_id = $project_id;
    }

    public function create($fields) {
        parent::create($fields);

        $supports = [
            'number' => REQUIRED
        ];

        if ($this->enforce($fields, $supports)) {
            $req = new APIRequest(
                $this->origin,
                '/v1/projects/' . $this->project_id . '/versions',
                'POST',
                ['params' => $fields]
            );

            return new ProjectVersion($this->origin, $req->exec());
        }
    }

    public function all($filters = []) {
        parent::all($filters);

        $request = new APIRequest($this->origin, '/v1/projects/' . $this->project_id . '/versions', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }
}
