<?php

namespace LeanTesting\API\Client\Handler\Project;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;

use LeanTesting\API\Client\Client;
use LeanTesting\API\Client\Entity\Bug\Bug;

class ProjectBugsHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Bug\\Bug';

    protected $project_id;

    public function __construct($origin, $project_id)
    {
        parent::__construct($origin);

        $this->project_id = $project_id;
    }

    public function create($fields)
    {
        parent::create($fields);

        $supports = [
            'title'              => Client::REQUIRED_PARAM,
            'status_id'          => Client::REQUIRED_PARAM,
            'severity_id'        => Client::REQUIRED_PARAM,
            'project_version'    => Client::REQUIRED_PARAM,
            'project_version_id' => Client::REQUIRED_PARAM,
            'project_section_id' => Client::OPTIONAL_PARAM,
            'type_id'            => Client::OPTIONAL_PARAM,
            'reproducibility_id' => Client::OPTIONAL_PARAM,
            'assigned_user_id'   => Client::OPTIONAL_PARAM,
            'description'        => Client::OPTIONAL_PARAM,
            'expected_results'   => Client::OPTIONAL_PARAM,
            'steps'              => Client::OPTIONAL_PARAM,
            'platform'           => Client::OPTIONAL_PARAM
            // 'device_model'       => Client::self::OPTIONAL_PARAM,
            // 'device_model_id'    => Client::self::OPTIONAL_PARAM,
            // 'os'                 => Client::self::OPTIONAL_PARAM,
            // 'os_version'         => Client::self::OPTIONAL_PARAM,
            // 'os_version_id'      => Client::self::OPTIONAL_PARAM,
            // 'browser_version_id' => Client::self::OPTIONAL_PARAM
        ];

        if (array_key_exists('project_version_id', $fields)) {
            $supports['project_version'] = Client::OPTIONAL_PARAM;
        } elseif (array_key_exists('project_version', $fields)) {
            $supports['project_version_id'] = Client::OPTIONAL_PARAM;
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

    public function all($filters = [])
    {
        parent::all($filters);

        $filters = array_merge(['include' => 'steps,platform,attachments,comments,tags'], $filters);

        $request = new APIRequest($this->origin, '/v1/projects/' . $this->project_id . '/bugs', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }
}
