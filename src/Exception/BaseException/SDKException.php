<?php

namespace LeanTesting\API\Client\Exception\BaseException;

class SDKException extends \Exception
{
    public function __construct($message = null) {
        if ($message == null) {
            $message = 'Unknown SDK Error';
        } else {
            $message = 'SDK Error: ' . $message;
        }

        parent::__construct($message);
    }
}
