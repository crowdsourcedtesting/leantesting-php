<?php

namespace LeanTesting\API\Client\Entity\Bug;

use LeanTesting\API\Client\BaseClass\Entity;

use LeanTesting\API\Client\Handler\Bug\BugCommentsHandler;
use LeanTesting\API\Client\Handler\Bug\BugAttachmentsHandler;



class Bug extends Entity
{
    public $comments;
    public $attachments;

    public function __construct($origin, $data) {
        parent::__construct($origin, $data);

        $this->comments    = new BugCommentsHandler($origin, $data['id']);
        $this->attachments = new BugAttachmentsHandler($origin, $data['id']);
    }
}
