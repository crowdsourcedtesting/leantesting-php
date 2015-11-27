<?php

namespace LeanTesting\API\Client\Handler\Attachment;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;

use LeanTesting\API\Client\Entity\Bug\BugAttachment;

class AttachmentsHandler extends EntityHandler
{
    public function find($id) {
        parent::find($id);

        $req = new APIRequest($this->origin, '/v1/attachments/' . $id, 'GET');
        return new BugAttachment($this->origin, $req->exec());
    }

    public function delete($id) {
        parent::delete($id);

        $req = new APIRequest($this->origin, '/v1/attachments/' . $id, 'DELETE');
        return $req->exec();
    }
}
