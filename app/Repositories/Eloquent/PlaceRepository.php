<?php

namespace App\Repositories\Eloquent;

use App\Models\Place;
use App\Repositories\Contracts\PlaceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PlaceRepository extends BaseRepository implements PlaceRepositoryInterface
{
    /**
     * PlaceRepository constructor.
     */
    public function __construct(Place $model)
    {
        parent::__construct($model);
    }

    /**
     * {@inheritDoc}
     */
    public function getByCategory(string $categorySlug, int $perPage = 10)
    {
        return $this->model->with('category')
            ->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * {@inheritDoc}
     */
    public function searchByName(string $query, int $limit = 5): Collection
    {
        return $this->model->where('name', 'like', "%{$query}%")
            ->select('id', 'name', 'slug', 'description', 'image_path', 'category_id') // Optimize selection
            ->with('category')
            ->take($limit)
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getPopular(int $limit = 6): Collection
    {
        // For now, latest. Could be based on views or rating later.
        return $this->model->with('category')
            ->latest()
            ->take($limit)
            ->get();
    }
}
