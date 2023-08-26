<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BaseRepositoryInterface
{

    /**
     * Query all
     *
     */
    public function all();

    /**
     * get all trashed rows
     *
     * @param $perPage pagination
     * @return Collection
     */
    public function allTrashed($perPage = 50);

    /**
     * Find row via ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Find rows via IDs
     *
     * @param array $ids
     * @return Collection
     */
    public function findByIds(array $ids);

    /**
     * Query with Relationsip
     *
     * @param array $relations
     * @return $this
     */
    public function with(array $relations);

    /**
     * Paginate query
     *
     * @param int $pages
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = 50);

    /**
     * Create a record in the database
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update a database record
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateViaId($id, array $data);

    /**
     * Update a specific column from the database via unique identifier
     *
     * @param array $identifier
     * @param string $key
     * @param array $data
     * @return mixed
     */
    public function updateKeyFromUniqueField($identifier, $key, $data);

    /**
     * Update a record from the database via unique identifier
     * @param array $identifier
     * @param array $data
     * @return mixed
     */
    public function updateFromUniqueField($identifier, array $data);

    /**
     * Adds filter to the query using model scopes
     *
     * @param string $scope the method name of the scope
     * @param mixed $value the parameter to pass into the scope
     * @return $this
     */
    public function addFilter(string $scopeName, $value);

    /**
     * Finds and deletes a record via ID
     * @param int $id
     */
    public function deleteViaId($id);

    /**
     * Finds and force deletes a record via ID
     * @param int $id
     */
    public function forceDeleteViaId($id);
}