<?php

namespace LeanTesting\API\Client\BaseClass;

use LeanTesting\API\Client\Client;
use LeanTesting\API\Client\Exception\SDKUnexpectedResponseException;

/**
 *
 * An EntityList is a list of Entity objects, obtained from compiling the results of an all() call.
 *
 * Note that this class implements PHP's Iterator interface, so is compatible with foreach() loops
 * Used within a foreach() loop, an EntityList will always start from page 1 (regardless of provided page) and end at
 * last page. Original limit filtering is preserved each iteration.
 *
 * Please consider remote requests volume when using foreach() on very large collections, or at least consider using
 * larger per_page limits in order to minimize number of outbound requests.
 *
 */
class EntityList implements \Iterator
{
    protected $origin;     // Reference to originating Client instance

    protected $identifier; // Class name identifier for the collection Entities

    protected $request;    // APIRequest definition to use for collection generation
    protected $filters;    // Filter list for generation (origins in Handler call)

    protected $pagination; // Pagination object as per response (without links)
    protected $real_page;  // Effective virtual paginator for out-of-bounds scenarios

    public $collection;    // Internal collection corresponding to current page

    /**
     *
     * (Re)generates internal collection data based on current iteration position.
     *
     * Regeneration is done every time position changes (i.e. every time repositioning functions are used).
     *
     * @throws SDKUnexpectedResponseException if no `meta` field is found
     * @throws SDKUnexpectedResponseException if no `pagination` field is found in `meta field`
     * @throws SDKUnexpectedResponseException if no collection set is found
     * @throws SDKUnexpectedResponseException if multiple collection sets are found
     *
     */
    protected function generateCollectionData()
    {
        $this->collection = []; // Clear previous collection data on fresh regeneration
        $this->pagination = []; // Clear previous pagination data on fresh regeneration

        $this->request->updateOpts(['params' => $this->filters]);
        $raw = $this->request->exec();

        if (!array_key_exists('meta', $raw)) {
            throw new SDKUnexpectedResponseException('missing `meta` field');
        } elseif (!array_key_exists('pagination', $raw['meta'])) {
            throw new SDKUnexpectedResponseException('`meta` missing `pagination` field');
        }

        if (array_key_exists('links', $raw['meta']['pagination'])) {
            unset($raw['meta']['pagination']['links']); // Remove not needed links sub-data
        }

        $this->pagination = $raw['meta']['pagination']; // Pass pagination data as per response meta key
        unset($raw['meta']);

        if (!count($raw)) {
            throw new SDKUnexpectedResponseException('collection object missing');
        } elseif (count($raw) > 1) {
            $cols = implode(', ', array_keys($raw));
            throw new SDKUnexpectedResponseException('expected one collection object, multiple received: ' . $cols);
        }

        $class_ident = $this->identifier; // Identifier to be used for dynamic Entity instancing
        foreach (reset($raw) as $entity) {
            array_push($this->collection, new $class_ident($this->origin, $entity));
        }
    }

    /**
     *
     * Constructs an Entity List instance.
     *
     * @param Client  $origin     Original client instance reference
     * @param APIRequest $request    An API Request definition given by the entity collection handler.
     *                               This is used for any subsequent collection regeneration, as any data updates are
     *                               dependant on external requests.
     * @param string     $identifier class name identifier to use for dynamic class instancing within list collection
     * @param mixed[]    $filters    original filters passed over from originating all() call
     *
     */
    public function __construct(Client $origin, APIRequest $request, $identifier, $filters = [])
    {
        $this->origin     = $origin;
        $this->request    = $request;
        $this->identifier = $identifier;
        $this->filters    = $filters;

        if (array_key_exists('page', $filters)) {
            $this->real_page = $filters['page'];
        } else {
            $this->real_page = 1;
        }

        $this->generateCollectionData();
    }

    /**
     *
     * Sets iterator position to first page. Ignored if already on first page.
     *
     */
    public function first()
    {
        if ($this->pagination['current_page'] === 1) {
            return false;
        }

        $this->filters['page'] = 1;
        $this->generateCollectionData();
        $this->real_page = 1;
    }

    /**
     *
     * Sets iterator position to previous page. Ignored if on first page.
     *
     */
    public function previous()
    {
        if ($this->pagination['current_page'] === 1) {
            return false;
        }

        --$this->filters['page'];
        $this->generateCollectionData();
        $this->real_page = $this->filters['page'];
    }

    /**
     *
     * Sets iterator position to next page. Ignored if on last page.
     * (required for Iterator implementation)
     *
     */
    public function next()
    {
        if ($this->pagination['current_page'] === $this->pagination['total_pages']) {
            ++$this->real_page;
            return false;
        }

        if (array_key_exists('page', $this->filters)) {
            ++$this->filters['page'];
        } else {
            $this->filters['page'] = 2;
        }

        $this->generateCollectionData();
        ++$this->real_page;
    }

    /**
     *
     * Sets iterator position to last page. Ignored if already on last page.
     *
     */
    public function last()
    {
        if ($this->pagination['current_page'] === $this->pagination['total_pages']) {
            return false;
        }

        $this->filters['page'] = $this->pagination['total_pages'];
        $this->generateCollectionData();
        $this->real_page = $this->pagination['total_pages'];
    }

    /**
     *
     * Alias of first().
     * (required for Iterator implementation)
     *
     */
    public function rewind()
    {
        $this->first();
    }

    /**
     *
     * Returns the Entity collection for the current page.
     * (required for Iterator implementation)
     *
     * @return Entity[] internal collection of Entity objects. Objects will be of child class types, not Entity parent.
     *
     */
    public function current()
    {
        return $this->toArray();
    }

    /**
     *
     * Returns current position. real_page is used in order to be compatible with foreach() functioning.
     * (required for Iterator implementation)
     *
     * @return integer the effective position of the iterator
     *
     */
    public function key()
    {
        return $this->real_page;
    }

    /**
     *
     * Function to check for termination of internal loop. foreach() depends on this (loop ends when false encountered).
     * (required for Iterator implementation)
     *
     * @return boolean true/false depending on whether or not last_page + 1 is detected
     *
     */
    public function valid()
    {
        $test = (
            $this->real_page === $this->pagination['current_page'] &&
            $this->real_page <= $this->pagination['total_pages']
        );

        if (!$test) {
            $this->real_page = $this->pagination['total_pages'];
        }

        return $test;
    }

    /**
     *
     * Outputs total number of Entities inside multi-page collection
     *
     * @return integer Number of total Entities
     *
     */
    public function total()
    {
        return $this->pagination['total'];
    }

    /**
     *
     * Outputs total number of pages the multi-page collection has, regardful of limit/per_page
     *
     * @return integer Number of total pages
     *
     */
    public function totalPages()
    {
        return $this->pagination['total_pages'];
    }

    /**
     *
     * Outputs number of Entities in current collection page.
     * Will always be same as limmit/per_page if not on last page.
     *
     * @return integer Number of Entities in page
     *
     */
    public function count()
    {
        return $this->pagination['count'];
    }

    /**
     *
     * Outputs internal collection in array format (converted from Entity objects)
     *
     * @return mixed[] array of converted array elements
     *
     */
    public function toArray()
    {
        $arr = [];
        foreach ($this->collection as $entity) {
            array_push($arr, $entity->data);
        }
        return $arr;
    }
}
