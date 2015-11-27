<?php

namespace LeanTesting\API\Client\BaseClass;

use LeanTesting\API\Client\Client;

use LeanTesting\API\Client\Exception\SDKInvalidArgException;
use LeanTesting\API\Client\Exception\SDKErrorResponseException;
use LeanTesting\API\Client\Exception\SDKBadJSONResponseException;
use LeanTesting\API\Client\Exception\SDKUnexpectedResponseException;

/**
 *
 * Represents an API Request definition.
 *
 * An APIRequest's parameters can be modified on demand and can be executed multiple times for the same instance.
 *
 */
class APIRequest
{

    protected $default_opts = [                       // Basic support for extended opts
        'base_uri'  => 'https://api.leantesting.com', // assumed default for API base
        'form_data' => false,                         // sets content type to multipart/form-data if true
        'params'    => []                             // params to be pased in request
    ];

    protected $origin;
    protected $endpoint;
    protected $method;
    protected $opts;

    /**
     *
     * Constructs API request definition.
     *
     * @param Client $origin   Originating client reference
     * @param string    $endpoint API endpoint
     * @param string    $method   Method for cURL call - supports GET, POST, PUT or DELETE only
     * @param mixed[]   $opts     (optional) Additional options to pass to request. Request parameters (if any) must be
     *                            passed here
     *
     * @throws SDKInvalidArgException if $method is non-string.
     * @throws SDKInvalidArgException if unsupported $method is provided.
     * @throws SDKInvalidArgException if $endpoint is non-string.
     * @throws SDKInvalidArgException if $opts param is not an array.
     *
     */
    public function __construct(Client $origin, $endpoint, $method, $opts = []) {
        if (!is_string($method)) {
            throw new SDKInvalidArgException('`$method` must be a string');
        } elseif (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
            throw new SDKInvalidArgException('unsupported ' . $method . ' `$method`');
        } elseif (!is_string($endpoint)) {
            throw new SDKInvalidArgException('`$endpoint` must be a string');
        } elseif (!is_array($opts)) {
            throw new SDKInvalidArgException('`$opts` must be an array');
        }

        $this->opts = $this->default_opts;
        $this->updateOpts($opts);

        $this->origin   = $origin;
        $this->endpoint = $endpoint;
        $this->method   = $method;
    }

    /**
     *
     * Updates options list inside API request definition.
     *
     * @param mixed[] $opts (optional) Additional options array to merge with previous option values
     *
     * @throws SDKInvalidArgException if $opts param is not an array.
     * @throws SDKInvalidArgException if provided parameter list is non-array parameter.
     *
     */
    public function updateOpts($opts = []) {
        if (!is_array($opts)) {
            throw new SDKInvalidArgException('`$opts` must be an array');
        } elseif (array_key_exists('params', $opts) && !is_array($opts['params'])) {
            throw new SDKInvalidArgException('`$opts[\'params\']` must be an array');
        }

        $this->opts = array_merge($this->opts, $opts);
    }

    /**
     *
     * Executes cURL call as per current API definition state.
     *
     * @return string Returns resulting data response from server (including errors and inconsistencies)
     *
     */
    public function call() {
        $ch = curl_init();

        $curl_headers = [];

        $call_url = $this->opts['base_uri'] . $this->endpoint;

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);

        switch ($this->method) { // Method-oriented parsing
            case 'GET':
                $call_url .= '?' . http_build_query($this->opts['params']);

                break;
            case 'POST':
            case 'PUT':
                if ($this->opts['form_data'] === true) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->opts['params']);

                    array_push($curl_headers, 'Content-Type: multipart/form-data');
                } else {
                    $json_data = json_encode($this->opts['params']);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

                    array_push($curl_headers, 'Content-Type: application/json');
                    array_push($curl_headers, 'Content-Length: ' . strlen($json_data));
                }

                break;
        }

        if (is_string($this->origin->getCurrentToken())) {
            array_push($curl_headers, 'Authorization: Bearer ' . $this->origin->getCurrentToken());
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_headers);
        curl_setopt($ch, CURLOPT_URL, $call_url);

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $curl_data = curl_exec($ch);
        $curl_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        $ch = null;

        return [
            'data' => $curl_data,
            'status' => $curl_status
        ];
    }

    /**
     *
     * Does cURL data interpretation
     *
     * @throws SDKErrorResponseException   if the remote response is an error.
     *         A server response is interpreted as an error if obtained status code differs from expected status code.
     *         Expected status codes are `200 OK` for GET/POST/PUT, `204 No Content` for DELETE.
     * @throws SDKBadJSONResponseException if the remote response contains erronated or invalid JSON contents
     *
     * @return mixed[]|boolean In case of successful request, a JSON decoded object is returned. If a DELETE request
     *         is issued, returns true if call is successful (exception otherwise).
     *
     */
    public function exec() {
        if ($this->origin->debug_return != null &&
            array_key_exists('data', $this->origin->debug_return) &&
            array_key_exists('status', $this->origin->debug_return)) {

            $curl_data = $this->origin->debug_return['data'];
            $curl_status = $this->origin->debug_return['status'];

        } else {
            $call = $this->call();
            $curl_data = $call['data'];
            $curl_status = $call['status'];
        }

        if ($this->method === 'DELETE') {
            $expected_http_status = 204;
        } else {
            $expected_http_status = 200;
        }

        if ($curl_status !== $expected_http_status) {
            throw new SDKErrorResponseException($curl_status . ' - ' . $curl_data);
        }

        if ($this->method === 'DELETE') {           // if DELETE request, expect no output
            return true;
        }

        $json_data = json_decode($curl_data, true); // normally, expect JSON qualified output
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new SDKBadJSONResponseException($curl_data);
        }

        if (!count($json_data)) {
            throw new SDKUnexpectedResponseException('Empty object received');
        }

        return $json_data;
    }
}
