<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

abstract class EloquentRepository implements BaseRepositoryInterface
{
    public $model;

    public $relations;

    public $filters = null;

    public $scopes = null;

    public $withCount;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    /**
     * Get all rows
     * @return LengthAwarePaginator
     */
    public function all()
    {
        $collection = $this->model
            ->when(!empty($this->relations), fn($q) => $q->with($this->relations))
            ->when(!empty($this->withCount), fn($q) => $q->withCount($this->withCount))
            ->paginate(10);

        return $collection;
    }

    /**
     * Get all trashed rows
     *
     * @param $perPage pagination
     * @return LengthAwarePaginator
     */
    public function allTrashed($perPage = 50)
    {
        $record = $this->model
            ->when(!empty($this->scopes), fn($q) => $this->getFilters($q))
            ->when(!empty($this->relations), fn($q) => $q->with($this->relations))
            ->onlyTrashed()
            ->paginate($perPage);

        return $record;
    }

    /**
     * Find row via ID
     *
     * @param int $id
     * @return object
     */
    public function findById($id): object
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * Find rows via IDs
     *
     * @param array $ids
     * @return Collection
     */
    public function findByIds(array $ids): Collection
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    /**
     * Include relationship to the call
     *
     * @param array $relations
     * @return $this
     */
    public function with(array $relations)
    {
        $this->relations = $relations;
        return $this;
    }

    /**
     * Paginate data
     *
     * @param $perPage pagination
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = 50): LengthAwarePaginator
    {
        $model = $this->model
            ->when(!empty($this->scopes), fn($q) => $this->getFilters($q))
            ->when(!empty($this->relations), fn($q) => $q->with($this->relations))
            ->paginate($perPage);

        return $model;
    }

    /**
     * Includes relationship to the call
     *
     * @param array $relations
     * @return $this
     */
    public function withCount(array $withCount)
    {
        $this->withCount = $withCount;
        return $this;
    }

    /**
     * Create a record in the database
     *
     * @param array $data
     * @return mixed
     */
    public function create($data)
    {

        return $this->model->create($data);
    }

    /**
     * Update a record from database via ID
     *
     * @param string|int $id
     * @param array $data
     * @return mixed
     */
    public function updateViaId($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Update a record from database via unique identifier
     *
     * @param array $identifier
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function updateKeyFromUniqueField($identifier, $key, $value)
    {
        return $this->model->where($identifier)->update([$key => $value]);
    }

    /**
     * Update a column from database via unique identifier
     *
     * @param array $identifier
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function updateFromUniqueField($identifier, $data)
    {
        return $this->model->where($identifier)->update($data);
    }

    /**
     * Add Scopes to the query
     *
     * @param string $scopeName the method name of the scope
     * @param mixed $value the parameter to pass into the scope
     * @return $this
     */
    public function addFilter(string $scopeName, $value)
    {
        $this->scopes[] = [
            'method' => $scopeName,
            'value' => $value
        ];
        return $this;
    }

    /**
     * Add this method to the chain to include scopes
     * @param Builder $q
     * @return Builder
     */
    public function getFilters($q)
    {
        foreach ($this->scopes as $key => $scope) {
            $scopeMethod = $scope['method'];
            $value = $scope['value'];

            $q->$scopeMethod($value);
        }

        return $q;
    }

    /**
     * Delete a record via ID
     *
     * @param int|string $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function deleteViaId($id)
    {
        return $this->model->findOrFail($id)->delete();
    }

    /**
     * Force deletes a record via ID
     * @param int|string $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function forceDeleteViaId($id)
    {
        return $this->model->findOrFail($id)->forceDelete();
    }

    /**
     * Bcrypt the password
     *
     */
    private function hash_password($password)
    {
        return Hash::make($password);
    }
}