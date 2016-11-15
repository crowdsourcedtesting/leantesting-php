<?php

namespace LeanTesting\API\Client\Handler\Bug;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;

use LeanTesting\API\Client\Client;
use LeanTesting\API\Client\Entity\Bug\Bug;

class BugsHandler extends EntityHandler
{
    public function find($id)
    {
        parent::find($id);

        $req = new APIRequest(
            $this->origin,
            '/v1/bugs/' . $id,
            'GET',
            [
                'params' => [
                    'include' => 'steps,platform,attachments,comments,tags'
                ]
            ]
        );
        return new Bug($this->origin, $req->exec());
    }

    public function delete($id)
    {
        parent::delete($id);

        $req = new APIRequest($this->origin, '/v1/bugs/' . $id, 'DELETE');
        return $req->exec();
    }

    public function update($id, $fields)
    {
        parent::update($id, $fields);

        $supports = [
            'title'              => Client::OPTIONAL_PARAM,
            'status_id'          => Client::OPTIONAL_PARAM,
            'severity_id'        => Client::OPTIONAL_PARAM,
            'priority_id'        => Client::OPTIONAL_PARAM,
            'project_version_id' => Client::OPTIONAL_PARAM,
            'project_section_id' => Client::OPTIONAL_PARAM,
            'type_id'            => Client::OPTIONAL_PARAM,
            'assigned_user_id'   => Client::OPTIONAL_PARAM,
            'description'        => Client::OPTIONAL_PARAM,
            'expected_results'   => Client::OPTIONAL_PARAM,
            'steps'              => Client::OPTIONAL_PARAM,
            'platform'           => Client::OPTIONAL_PARAM
            // 'device_model'       => OPTIONAL,
            // 'device_model_id'    => OPTIONAL,
            // 'os'                 => OPTIONAL,
            // 'os_version'         => OPTIONAL,
            // 'os_version_id'      => OPTIONAL,
            // 'browser_version_id' => OPTIONAL
        ];

        if ($this->enforce($fields, $supports)) {
            $fields = array_merge(['include' => 'steps,platform'], $fields);

            $req = new APIRequest($this->origin, '/v1/bugs/'. $id, 'PUT', ['params' => $fields]);
            return new Bug($this->origin, $req->exec());
        }
    }
}
