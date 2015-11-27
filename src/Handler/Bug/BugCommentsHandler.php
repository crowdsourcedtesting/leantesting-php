<?php

namespace LeanTesting\API\Client\Handler\Bug;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;

class BugCommentsHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Bug\\BugComment';

    protected $bug_id;

    public function __construct($origin, $bug_id) {
        parent::__construct($origin);

        $this->bug_id = $bug_id;
    }

    public function all($filters = []) {
        parent::all($filters);

        $request = new APIRequest($this->origin, '/v1/bugs/' . $this->bug_id . '/comments', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }
}
