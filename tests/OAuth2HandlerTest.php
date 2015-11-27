<?php

namespace LeanTesting\API\Client\Test;

use LeanTesting\API\Client\Client;

use LeanTesting\API\Client\Handler\Auth\OAuth2Handler;

class OAuth2HandlerTest extends \PHPUnit_Framework_TestCase
{

    public function testOAuth2HandlerDefined() {
        $this->assertTrue(class_exists('LeanTesting\API\Client\Handler\Auth\OAuth2Handler'));
    }







    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testOAuth2HandlerGenerateNonStrClientID() {
        (new OAuth2Handler(new Client))->generateAuthLink(1, '', '');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testOAuth2HandlerGenerateNonStrRedirectURI() {
        (new OAuth2Handler(new Client))->generateAuthLink('', 1, '');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testOAuth2HandlerGenerateNonStrScope() {
        (new OAuth2Handler(new Client))->generateAuthLink('', '', 1);
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testOAuth2HandlerGenerateNonStrState() {
        (new OAuth2Handler(new Client))->generateAuthLink('', '', '', 1);
    }






    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testOAuth2HandlerExchangeNonStrClientID() {
        $client = new Client;
        $client->debug_return = '{}';
        (new OAuth2Handler($client))->exchangeAuthCode(1, '', '', '', '');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testOAuth2HandlerExchangeNonStrClientSecret() {
        $client = new Client;
        $client->debug_return = '{}';
        (new OAuth2Handler($client))->exchangeAuthCode('', 1, '', '', '');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testOAuth2HandlerExchangeNonStrGrantType() {
        $client = new Client;
        $client->debug_return = '{}';
        (new OAuth2Handler($client))->exchangeAuthCode('', '', 1, '', '');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testOAuth2HandlerExchangeNonStrCode() {
        $client = new Client;
        $client->debug_return = '{}';
        (new OAuth2Handler($client))->exchangeAuthCode('', '', '', 1, '');
    }
    /**
     * @expectedException \LeanTesting\API\Client\Exception\SDKInvalidArgException
     */
    public function testOAuth2HandlerExchangeNonStrRedirectURI() {
        $client = new Client;
        $client->debug_return = '{}';
        (new OAuth2Handler($client))->exchangeAuthCode('', '', '', '', 1);
    }


}

