<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    /**
     * Get all models.
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Get all models paginated.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = []);

    /**
     * Find a model by its primary key.
     */
    public function find(int $id, array $columns = ['*'], array $relations = []): ?Model;

    /**
     * Find a model by specific criteria.
     */
    public function findBy(array $criteria, array $columns = ['*'], array $relations = []): ?Model;

    /**
     * Create a new model.
     */
    public function create(array $payload): Model;

    /**
     * Update an existing model.
     */
    public function update(int $id, array $payload): Model|bool;

    /**
     * Delete a model by its primary key.
     */
    public function delete(int $id): bool;
}
