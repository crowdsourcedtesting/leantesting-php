<?php

namespace LeanTesting\API\Client\Exception;

use LeanTesting\API\Client\Exception\BaseException\SDKException;

class SDKUnexpectedResponseException extends SDKException
{
    private $base_message = 'Got unexpected remote response';

    public function __construct($message = null) {
        if ($message == null) {
            $message = $this->base_message;
        } else {
            $message = $this->base_message . ' - ' . $message;
        }

        parent::__construct($message);
    }
}
