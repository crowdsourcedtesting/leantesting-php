<?php

namespace LeanTesting\API\Client\Test;

use LeanTesting\API\Client\BaseClass\EntityList;
use LeanTesting\API\Client\Client;
use Mockery;

class EntityListTest extends \PHPUnit_Framework_TestCase
{

    /* EntityList */
    public function testEntityListDefined() {
        $this->assertTrue(class_exists('LeanTesting\API\Client\BaseClass\EntityList'));
    }




    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKUnexpectedResponseException
     * @expectedExceptionMessage meta
     */
    public function testEntityListBadRespMissingMeta() {
        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'GET']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => '{"x": []}', 'status' => 200]);

        new EntityList(new Client, $mock, 'X');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKUnexpectedResponseException
     * @expectedExceptionMessage pagination
     */
    public function testEntityListBadRespMissingMetaPagination() {
        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'GET']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => '{"x": [], "meta": {}}', 'status' => 200]);

        new EntityList(new Client, $mock, 'X');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKUnexpectedResponseException
     * @expectedExceptionMessage collection
     */
    public function testEntityListBadRespMissingCollection() {
        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'GET']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => '{"meta": {"pagination": {}}}', 'status' => 200]);

        new EntityList(new Client, $mock, 'X');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKUnexpectedResponseException
     * @expectedExceptionMessage multiple
     */
    public function testEntityListBadRespMultipleCollections() {
        $mock = \Mockery::mock('LeanTesting\API\Client\BaseClass\APIRequest[call]', [new Client, '/any/method', 'GET']);
        $mock->shouldReceive('call')->withNoArgs()->andReturn(['data' => '{"xxy": [], "yyx": [], "meta": {"pagination": {}}}', 'status' => 200]);

        new EntityList(new Client, $mock, 'X');
    }
    /* END EntityList */

}
