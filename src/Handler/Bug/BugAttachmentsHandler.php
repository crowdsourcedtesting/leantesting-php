<?php

namespace LeanTesting\API\Client\Handler\Bug;

use LeanTesting\API\Client\BaseClass\APIRequest;
use LeanTesting\API\Client\BaseClass\EntityHandler;
use LeanTesting\API\Client\BaseClass\EntityList;

use LeanTesting\API\Client\Exception\SDKInvalidArgException;

use LeanTesting\API\Client\Entity\Bug\BugAttachment;

class BugAttachmentsHandler extends EntityHandler
{
    private $return_class = 'LeanTesting\\API\\Client\\Entity\\Bug\\BugAttachment';

    protected $bug_id;

    public function __construct($origin, $bug_id) {
        parent::__construct($origin);

        $this->bug_id = $bug_id;
    }

    /**
     *
     * Returns file identifier, in the form of @-prefixed strings for PHP <=5.4 or CurlFile objects for PHP >=5.5
     *
     * @see https://github.com/guzzle/guzzle/blob/3a0787217e6c0246b457e637ddd33332efea1d2a/src/Guzzle/Http/Message/PostFile.php#L90
     *
     * @param string $filepath an absolute path of the file to be uploaded (mandatory realpath format)
     *                         example: /home/path/to/file.txt (Linux), C:\Users\Documents\file.txt (Windows)
     *
     * @return mixed file identifier
     *
     */
    private function getCurlFile($filepath) {
        $content_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filepath);
        $target_name  = basename($filepath);

        if (function_exists('curl_file_create')) {
            return curl_file_create($filepath, $content_type, $target_name);
        }

        $value = '@' . $filepath . ';filename=' . $target_name;
        if ($content_type) {
            $value .= ';type=' . $content_type;
        }

        return $value;
    }

    /**
     *
     * Uploads given file as an attachment for specified bug.
     *
     * Please note that this function utilizes @ prefixed file upload identifiers for PHP 5.4 compatibility.
     * While PHP 5.5 accepts this (E_WARNING), consider upgrading to CurlFile for PHP >= 5.6 compatibility.
     *
     * @param string $filepath an absolute path of the file to be uploaded (mandatory realpath format)
     *                         example: /home/path/to/file.txt (Linux), C:\Users\Documents\file.txt (Windows)
     *
     * @throws SDKInvalidArgException if $filepath is not a string
     *
     * @return BugAttachment the newly uploaded attachment
     *
     */
    public function upload($filepath) {
        if (!is_string($filepath)) {
            throw new SDKInvalidArgException('`$filepath` must be of type string');
        }

        $req = new APIRequest(
            $this->origin,
            '/v1/bugs/' . $this->bug_id . '/attachments',
            'POST',
            [
                'form_data'       => true,
                'params'          => [
                    'file'        => $this->getCurlFile($filepath)
                ]
            ]
        );

        return new BugAttachment($this->origin, $req->exec());
    }

    public function all($filters = []) {
        parent::all($filters);

        $request = new APIRequest($this->origin, '/v1/bugs/' . $this->bug_id . '/attachments', 'GET');
        return new EntityList($this->origin, $request, $this->return_class, $filters);
    }
}
