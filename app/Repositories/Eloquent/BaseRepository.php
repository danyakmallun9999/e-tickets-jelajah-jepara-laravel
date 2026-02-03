<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * {@inheritDoc}
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    /**
     * {@inheritDoc}
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = [])
    {
        return $this->model->with($relations)->select($columns)->latest()->paginate($perPage);
    }

    /**
     * {@inheritDoc}
     */
    public function find(int $id, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->with($relations)->select($columns)->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findBy(array $criteria, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->with($relations)->select($columns)->where($criteria)->first();
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $payload): Model
    {
        return $this->model->create($payload);
    }

    /**
     * {@inheritDoc}
     */
    public function update(int $id, array $payload): Model|bool
    {
        $model = $this->find($id);

        if (! $model) {
            return false;
        }

        $model->update($payload);

        return $model->fresh();
    }

    /**
     * {@inheritDoc}
     */
    public function delete(int $id): bool
    {
        $model = $this->find($id);

        if (! $model) {
            return false;
        }

        return $model->delete();
    }
}
