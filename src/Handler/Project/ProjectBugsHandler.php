<?php

namespace LeanTesting\API\Client\Handler\Project;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;

use LeanTesting\API\Client\Entity\Bug\Bug;

class ProjectBugsHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Bug\\Bug';

    protected $project_id;

    public function __construct($origin, $project_id) {
        parent::__construct($origin);

        $this->project_id = $project_id;
    }

    public function create($fields) {
        parent::create($fields);

        $supports = [
            'title'              => REQUIRED,
            'status_id'          => REQUIRED,
            'severity_id'        => REQUIRED,
            'project_version'    => REQUIRED,
            'project_version_id' => REQUIRED,
            'project_section_id' => OPTIONAL,
            'type_id'            => OPTIONAL,
            'reproducibility_id' => OPTIONAL,
            'assigned_user_id'   => OPTIONAL,
            'description'        => OPTIONAL,
            'expected_results'   => OPTIONAL,
            'steps'              => OPTIONAL,
            'platform'           => OPTIONAL
            // 'device_model'       => OPTIONAL,
            // 'device_model_id'    => OPTIONAL,
            // 'os'                 => OPTIONAL,
            // 'os_version'         => OPTIONAL,
            // 'os_version_id'      => OPTIONAL,
            // 'browser_version_id' => OPTIONAL
        ];

        if (array_key_exists('project_version_id', $fields)) {
            $supports['project_version'] = OPTIONAL;
        } elseif (array_key_exists('project_version', $fields)) {
            $supports['project_version_id'] = OPTIONAL;
        }

        if ($this->enforce($fields, $supports)) {
            $fields = array_merge(['include' => 'steps,platform'], $fields);

            $req = new APIRequest(
                $this->origin,
                '/v1/projects/' . $this->project_id . '/bugs',
                'POST',
                ['params' => $fields]
            );

            return new Bug($this->origin, $req->exec());
        }
    }

    public function all($filters = []) {
        parent::all($filters);

        $filters = array_merge(['include' => 'steps,platform,attachments,comments,tags'], $filters);

        $request = new APIRequest($this->origin, '/v1/projects/' . $this->project_id . '/bugs', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }
}
