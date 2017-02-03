<?php
namespace LeanTesting\API\Client\Handler\Project;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;
use LeanTesting\API\Client\Client;
use LeanTesting\API\Client\Entity\Project\ProjectWebhook;

class ProjectWebhooksHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Project\\ProjectWebhook';

    protected $project_id;

    protected $events = [
        'bug_create',
        'bug_create_severity_critical',
        'bug_create_severity_major',
        'bug_create_priority_critical',
        'bug_create_priority_high',
        'bug_edit',
        'bug_assign',
        'bug_assign_target',
        'bug_status_change',
        'bug_resolved',
        'bug_move',
        'bug_delete',
        'comment_create',
        'comment_edit',
        'message_create',
        'message_edit',
        'attachment_create',
        'run_start',
        'run_finish',
    ];

    public function __construct($origin, $project_id)
    {
        parent::__construct($origin);

        $this->project_id = $project_id;
    }

    /**
     * @param int $id
     * @return ProjectWebhook
     */
    public function find($id)
    {
        parent::find($id);

        $endpoint = sprintf('/v1/projects/%s/integrations/webhooks/%s', $this->project_id, $id);
        $req      = new APIRequest($this->origin, $endpoint, 'GET');
        return new ProjectWebhook($this->origin, $req->exec());
    }

    /**
     * @param array $fields
     * @return ProjectWebhook
     */
    public function create($fields)
    {
        parent::create($fields);

        $supports = array_merge(['url' => Client::REQUIRED_PARAM],
            array_fill_keys($this->events, Client::OPTIONAL_PARAM));

        if ($this->enforce($fields, $supports)) {
            $endpoint = sprintf('/v1/projects/%s/integrations/webhooks', $this->project_id);
            $req      = new APIRequest($this->origin, $endpoint, 'POST', ['params' => $fields]);
            return new ProjectWebhook($this->origin, $req->exec());
        }
    }


    public function delete($id)
    {
        parent::delete($id);

        $endpoint = sprintf('/v1/projects/%s/integrations/webhooks/%s', $this->project_id, $id);
        $req      = new APIRequest($this->origin, $endpoint, 'DELETE');
        return $req->exec();
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
            '/v1/projects/' . $this->project_id . '/integrations/webhooks',
            'GET'
        );
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }
}