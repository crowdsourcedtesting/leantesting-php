<?php

namespace LeanTesting\API\Client\BaseClass;

use LeanTesting\API\Client\Client;
use LeanTesting\API\Client\Exception\SDKInvalidArgException;
use LeanTesting\API\Client\Exception\SDKUnsupportedRequestException;
use LeanTesting\API\Client\Exception\SDKIncompleteRequestException;
use LeanTesting\API\Client\Exception\SDKDuplicateRequestException;

/**
 *
 * An EntityHandler is the equivalent of a method centralizer for a corresponding endpoint (such as /v1/entities).
 *
 * Functional naming conventions and equivalents:
 *   create(fields)      <=>  `Create a new Entity`
 *   all(fields)         <=>  `List all Entities`
 *   find(id)            <=>  `Retrieve an existing Entity`
 *   delete(id)          <=>  `Delete an Entity`
 *   update(id, fields)  <=>  `Update an Entity`
 *
 */
class EntityHandler
{
    protected $origin; // Reference to originating Client instance

    public function __construct(Client $origin)
    {
        $this->origin = $origin;
    }

    /**
     *
     * Function definition for creating a new entity. Base function checks for invalid parameters.
     *
     * @param mixed[] $fields Non-empty array consisting of entity data to send for adding
     *
     * @throws SDKInvalidArgException if provided $fields param is not an array.
     * @throws SDKInvalidArgException if provided $fields param is empty.
     *
     */
    public function create($fields)
    {
        if (!is_array($fields)) {
            throw new SDKInvalidArgException('`$fields` must be an array');
        } elseif (!count($fields)) {
            throw new SDKInvalidArgException('`$fields` must be non-empty');
        }
    }

    /**
     *
     * Function definition for listing all entities. Base function checks for invalid parameters.
     *
     * @param mixed[] $filters (optional) Filters to apply to restrict listing. Currently supported: limit, page
     *
     * @throws SDKInvalidArgException if provided $filters param is not an array.
     * @throws SDKInvalidArgException if invalid filter value found in $filters array.
     *
     */
    public function all($filters = [])
    {
        if (!is_array($filters)) {
            throw new SDKInvalidArgException('`$filters` must be an array');
        } else {
            foreach ($filters as $fk => $fv) {
                if (!in_array($fk, ['include', 'limit', 'page', 'select', 'has', 'filter'])) {
                    throw new SDKInvalidArgException('unsupported ' . $fk . ' for `$filters`');
                }
            }
        }
    }

    /**
     *
     * Function definition for retrieving an existing entity. Base function checks for invalid parameters.
     *
     * @param int $id ID field to look for in the entity collection
     *
     * @throws SDKInvalidArgException if provided $id param is not an integer.
     *
     */
    public function find($id)
    {
        if (!is_int($id)) {
            throw new SDKInvalidArgException('`$id` must be of type integer');
        }
    }

    /**
     *
     * Function definition for deleting an existing entity. Base function checks for invalid parameters.
     *
     * @param int $id ID field of entity to delete in the entity collection
     *
     * @throws SDKInvalidArgException if provided $id param is not an integer.
     *
     */
    public function delete($id)
    {
        if (!is_int($id)) {
            throw new SDKInvalidArgException('`$id` must be of type integer');
        }
    }

    /**
     *
     * Function definition for updating an existing entity. Base function checks for invalid parameters.
     *
     * @param int     $id     ID field of entity to update in the entity collection
     * @param mixed[] $fields Non-empty array consisting of entity data to send for update
     *
     * @throws SDKInvalidArgException if provided $id param is not an integer.
     * @throws SDKInvalidArgException if provided $fields param is not an array.
     * @throws SDKInvalidArgException if provided $fields param is empty.
     *
     */
    public function update($id, $fields)
    {
        if (!is_int($id)) {
            throw new SDKInvalidArgException('`$id` must be of type integer');
        } elseif (!is_array($fields)) {
            throw new SDKInvalidArgException('`$fields` must be an array');
        } elseif (!count($fields)) {
            throw new SDKInvalidArgException('`$fields` must be non-empty');
        }
    }

    /**
     *
     * Helper function that enforces a structure based on a supported table:
     *   - Forces use of Client::REQUIRED_PARAM fields
     *   - Detects duplicate fields
     *   - Detects unsupported fields
     *
     * @param mixed[] $array Array to be enforced
     * @param mixed[] $supports Support table consisting of Client::REQUIRED_PARAM and
     * Client::OPTIONAL_PARAM keys to be used in enforcing
     *
     * @return bool
     * @throws SDKUnsupportedRequestException if unsupported fields are found
     * @throws SDKIncompleteRequestException  if any required field is missing
     * @throws SDKDuplicateRequestException   if any duplicate field is found
     *
     */
    protected function enforce($array, $supports)
    {
        $sall  = [];        // All supported keys
        $sreq  = [];        // Mandatory supported keys

        $socc  = $supports; // Key use occurances

        $unsup = [];        // Unsupported key list
        $dupl  = [];        // Duplicate key list
        $mreq  = [];        // Missing required keys

        foreach ($supports as $sk => $sv) {
            if ($sv === Client::REQUIRED_PARAM) {
                array_push($sreq, $sk);
            }
            array_push($sall, $sk);
            $socc[$sk] = 0;
        }

        foreach ($array as $k => $v) {
            if (in_array($k, $sall)) {
                $socc[$k]++;
            } else {
                array_push($unsup, $k);
            }
        }

        foreach ($socc as $ok => $ov) {
            if ($ov > 1) {
                array_push($dupl, $ok);
            } elseif ($ov === 0 && in_array($ok, $sreq)) {
                array_push($mreq, $ok);
            }
        }

        if (count($unsup)) {
            throw new SDKUnsupportedRequestException($unsup);
        } elseif (count($mreq)) {
            throw new SDKIncompleteRequestException($mreq);
        } elseif (count($dupl)) {
            throw new SDKDuplicateRequestException($dupl);
        }

        return true;
    }
}
