<?php

namespace LeanTesting\API\Client\Test;

use Mockery;

use LeanTesting\API\Client\Client;

use LeanTesting\API\Client\BaseClass\APIRequest;

class APIRequestTest extends \PHPUnit_Framework_TestCase
{

    /* APIRequest */
    public function testAPIRequestDefined() {
        $this->assertTrue(class_exists('LeanTesting\API\Client\BaseClass\APIRequest'));
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testAPIRequestInstanceNonStrEndpoint() {
        new APIRequest(new Client, 12751, 'GET');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testAPIRequestInstanceNonStrMethod() {
        new APIRequest(new Client, '/', 131231);
    }
    public function testAPIRequestInstanceSupportedMethod() {
        new APIRequest(new Client, '/', 'GET');
        new APIRequest(new Client, '/', 'POST');
        new APIRequest(new Client, '/', 'PUT');
        new APIRequest(new Client, '/', 'DELETE');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testAPIRequestInstanceNonSupportedMethod() {
        new APIRequest(new Client, '/', 'XXX');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testAPIRequestInstanceNonArrOpts() {
        new APIRequest(new Client, '/', 'GET', 111223);
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKBadJSONResponseException
     * @expectedExceptionMessage {xxxxxxxxx
     */
    public function testAPIRequestBadJSONResponse() {
        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'GET']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => '{xxxxxxxxx', 'status' => 200]);
        $mock->exec();
    }






    public function testAPIRequestParseResponse() {
        $mock_resp = '{"x1": 123, "x2_x2": "str::__str", "objx111": {"v1": "v1", "v2": 2, "d": [1,2,"3", []]}}';

        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'GET']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => $mock_resp, 'status' => 200]);

        $this->assertSame(json_decode($mock_resp, true), $mock->exec());
    }





    public function testAPIRequestExpectedStatus() {
        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'GET']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => '{"X": "X"}', 'status' => 200]);
        $mock->exec();

        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'POST']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => '{"X": "X"}', 'status' => 200]);
        $mock->exec();

        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'PUT']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => '{"X": "X"}', 'status' => 200]);
        $mock->exec();

        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'DELETE']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => '1', 'status' => 204]);
        $mock->exec();
    }







    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKErrorResponseException
     * @expectedExceptionMessage XXXyyy
     */
    public function testAPIRequestUnexpectedStatusDELETE() {
        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'DELETE']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => 'XXXyyy', 'status' => 200]);
        $mock->exec();
    }








    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKErrorResponseException
     * @expectedExceptionMessage XXXyyy
     */
    public function testAPIRequestUnexpectedStatusGET() {
        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'GET']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => 'XXXyyy', 'status' => 204]);
        $mock->exec();
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKErrorResponseException
     * @expectedExceptionMessage XXXyyy
     */
    public function testAPIRequestUnexpectedStatusPOST() {
        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'POST']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => 'XXXyyy', 'status' => 204]);
        $mock->exec();
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKErrorResponseException
     * @expectedExceptionMessage XXXyyy
     */
    public function testAPIRequestUnexpectedStatusPUT() {
        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'PUT']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => 'XXXyyy', 'status' => 204]);
        $mock->exec();
    }





    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKUnexpectedResponseException
     * @expectedExceptionMessage Empty
     */
    public function testAPIRequestEmptyRespGET() {
        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'GET']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => '{}', 'status' => 200]);
        $mock->exec();
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKUnexpectedResponseException
     * @expectedExceptionMessage Empty
     */
    public function testAPIRequestEmptyRespPOST() {
        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'POST']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => '{}', 'status' => 200]);
        $mock->exec();
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKUnexpectedResponseException
     * @expectedExceptionMessage Empty
     */
    public function testAPIRequestEmptyRespPUT() {
        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'PUT']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => '{}', 'status' => 200]);
        $mock->exec();
    }
    /* END APIRequest */

}
