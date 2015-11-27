<?php
namespace LeanTesting\API\Client\BaseClass;

use LeanTesting\API\Client\Client;
use LeanTesting\API\Client\Exception\SDKInvalidArgException;

/**
 *
 * Represents a single Entity. All remote responses are decoded and parsed into one or more Entities.
 *
 */
class Entity
{
    public $origin; // Reference to originating Client instance
    public $data;   // Internal entity object data

    /**
     *
     * Constructs an Entity instance
     *
     * @param Client $origin Original client instance reference
     * @param mixed[]   $data   Data to be contained in the new Entity. Must be non-empty.
     *
     * @throws SDKInvalidArgException if provided $data param is not an array.
     * @throws SDKInvalidArgException if provided $data param is empty. Entities cannot be empty.
     *
     */
    public function __construct(Client $origin, $data)
    {
        if (!is_array($data)) {
            throw new SDKInvalidArgException('`$data` must be an array');
        } elseif (!count($data)) {
            throw new SDKInvalidArgException('`$data` must be non-empty');
        }

        $this->origin = $origin;
        $this->data   = $data;
    }
}
