<?php
namespace LeanTesting\API\Client\Test;

use LeanTesting\API\Client\Exception\SDKDuplicateRequestException;
use LeanTesting\API\Client\Exception\SDKIncompleteRequestException;
use LeanTesting\API\Client\Exception\SDKUnsupportedRequestException;


class ExceptionsTest extends \PHPUnit_Framework_TestCase
{

    private $exception_colllection = [
        ['LeanTesting\API\Client\Exception\BaseException\SDKException'],
        ['LeanTesting\API\Client\Exception\SDKBadJSONResponseException'],
        ['LeanTesting\API\Client\Exception\SDKDuplicateRequestException'],
        ['LeanTesting\API\Client\Exception\SDKErrorResponseException'],
        ['LeanTesting\API\Client\Exception\SDKIncompleteRequestException'],
        ['LeanTesting\API\Client\Exception\SDKInvalidArgException'],
        ['LeanTesting\API\Client\Exception\SDKMissingArgException'],
        ['LeanTesting\API\Client\Exception\SDKUnexpectedResponseException'],
        ['LeanTesting\API\Client\Exception\SDKUnsupportedRequestException']
    ];

    public function testExceptionsDefined() {
        foreach ($this->exception_colllection as $e) {
            $this->assertTrue(class_exists($e[0]));
        }
    }

    public function testExceptionsRaiseNoArgs() {
        foreach ($this->exception_colllection as $e) {
            try {
                throw new $e[0]();
                $this->fail('No exception thrown. Expected ' . $e[0]);
            } catch(\Exception $ex) {
                if (get_class($ex) !== $e[0]) {
                    $this->fail('Unexpected exception received. Expected ' . $e[0]);
                }
                if (strpos($ex->getMessage(), 'SDK Error') === false) {
                    $this->fail('Unexpected exception message for ' . $e[0]);
                }
            }
        }
    }

    public function testExceptionsRaiseWithStr() {
        foreach ($this->exception_colllection as $e) {
            try {
                throw new $e[0]('XXXmsgXXX');
                $this->fail('No exception thrown. Expected ' . $e[0]);
            } catch(\Exception $ex) {
                if (get_class($ex) !== $e[0]) {
                    $this->fail('Unexpected exception received. Expected ' . $e[0]);
                }
                if (strpos($ex->getMessage(), 'XXXmsgXXX') === false) {
                    $this->fail('Unexpected exception message for ' . $e[0]);
                }
            }
        }
    }


    /* RAISE WITH ARR (when supported) */
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKDuplicateRequestException
     * @expectedExceptionMessage Duplicate
     */
    public function testDuplicateRequestRaiseWithArr() {
        throw new SDKDuplicateRequestException(['xx', 'yy', 'zz']);
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKIncompleteRequestException
     * @expectedExceptionMessage Incomplete
     */
    public function testIncompleteRequestRaiseWithArr() {
        throw new SDKIncompleteRequestException(['xx', 'yy', 'zz']);
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKUnsupportedRequestException
     * @expectedExceptionMessage Unsupported
     */
    public function testUnsupportedRequestRaiseWithArr() {
        throw new SDKUnsupportedRequestException(['xx', 'yy', 'zz']);
    }
    /* END RAISE WITH ARR */

}
