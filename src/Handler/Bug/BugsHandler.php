<?php

namespace LeanTesting\API\Client\Handler\Bug;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;

use LeanTesting\API\Client\Entity\Bug\Bug;

class BugsHandler extends EntityHandler
{
    public function find($id) {
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

    public function delete($id) {
        parent::delete($id);

        $req = new APIRequest($this->origin, '/v1/bugs/' . $id, 'DELETE');
        return $req->exec();
    }

    public function update($id, $fields) {
        parent::update($id, $fields);

        $supports = [
            'title'              => OPTIONAL,
            'status_id'          => OPTIONAL,
            'severity_id'        => OPTIONAL,
            'project_version_id' => OPTIONAL,
            'project_section_id' => OPTIONAL,
            'type_id'            => OPTIONAL,
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

        if ($this->enforce($fields, $supports)) {
            $fields = array_merge(['include' => 'steps,platform'], $fields);

            $req = new APIRequest($this->origin, '/v1/bugs/'. $id, 'PUT', ['params' => $fields]);
            return new Bug($this->origin, $req->exec());
        }
    }
}
