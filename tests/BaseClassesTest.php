<?php

namespace LeanTesting\API\Client\Test;

use LeanTesting\API\Client\Client;

use LeanTesting\API\Client\BaseClass\Entity;
use LeanTesting\API\Client\BaseClass\EntityHandler;

class BaseClassesTest extends \PHPUnit_Framework_TestCase
{

    /* Entity */
    public function testEntityDefined() {
        $this->assertTrue(class_exists('LeanTesting\API\Client\BaseClass\Entity'));
    }
    public function testEntityDataParsing() {
        $data = ['id' => 1];
        $entity = new Entity(new Client, $data);
        $this->assertSame($entity->data, $data);
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testEntityInstanceNonArrData() {
        $entity = new Entity(new Client, '');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testEntityInstanceEmptyData() {
        $entity = new Entity(new Client, []);
    }
    /* END Entity */





    /* EntityHandler */
    public function testEntityHandlerDefined() {
        $this->assertTrue(class_exists('LeanTesting\API\Client\BaseClass\EntityHandler'));
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testEntityHandlerCreateNonArrFields() {
        (new EntityHandler(new Client))->create('');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testEntityHandlerCreateEmptyFields() {
        (new EntityHandler(new Client))->create([]);
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testEntityHandlerAllNonArrFilters() {
        (new EntityHandler(new Client))->all('');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     * @expectedExceptionMessage XXXfilterXXX
     */
    public function testEntityHandlerAllInvalidFilters() {
        (new EntityHandler(new Client))->all(['XXXfilterXXX' => 9999]);
    }
    public function testEntityHandlerAllSupportedFilters() {
        (new EntityHandler(new Client))->all(['include' => '']);
        (new EntityHandler(new Client))->all(['limit' => 10]);
        (new EntityHandler(new Client))->all(['page' => 2]);
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testEntityHandlerFindNonIntID() {
        (new EntityHandler(new Client))->find('');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testEntityHandlerDeleteNonIntID() {
        (new EntityHandler(new Client))->delete('');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testEntityHandlerUpdateNonIntID() {
        (new EntityHandler(new Client))->update('', ['x' => 5]);
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testEntityHandlerUpdateNonArrFields() {
        (new EntityHandler(new Client))->update(5, '');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testEntityHandlerUpdateEmptyFields() {
        (new EntityHandler(new Client))->update(5, []);
    }
    /* END EntityHandler */

}
