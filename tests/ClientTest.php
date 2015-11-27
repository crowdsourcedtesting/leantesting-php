<?php

namespace LeanTesting\API\Client\Test;

use LeanTesting\API\Client\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    public function testClientDefined() {
        $this->assertTrue(class_exists('LeanTesting\API\Client\Client'));
    }

    public function testClientHasAuthObj() {
        $this->assertInstanceOf('LeanTesting\API\Client\Handler\Auth\OAuth2Handler', (new Client)->auth);
    }
    public function testClientHasUserObj() {
        $this->assertInstanceOf('LeanTesting\API\Client\Handler\User\UserHandler', (new Client)->user);
    }
    public function testClientHasProjectsObj() {
        $this->assertInstanceOf('LeanTesting\API\Client\Handler\Project\ProjectsHandler', (new Client)->projects);
    }
    public function testClientHasBugsObj() {
        $this->assertInstanceOf('LeanTesting\API\Client\Handler\Bug\BugsHandler', (new Client)->bugs);
    }
    public function testClientHasAttachmentsObj() {
        $this->assertInstanceOf('LeanTesting\API\Client\Handler\Attachment\AttachmentsHandler', (new Client)->attachments);
    }
    public function testClientHasPlatformObj() {
        $this->assertInstanceOf('LeanTesting\API\Client\Handler\Platform\PlatformHandler', (new Client)->platform);
    }

    public function testInitialEmptyToken() {
        $this->assertFalse((new Client)->getCurrentToken());
    }
    public function testTokenParseStorage() {
        $token_name = '__token__test__';
        $client = new Client;
        $client->attachToken($token_name);
        $this->assertEquals($client->getCurrentToken(), $token_name);
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testTokenParseNonStr() {
        (new Client)->attachToken(7182381);
    }

}
