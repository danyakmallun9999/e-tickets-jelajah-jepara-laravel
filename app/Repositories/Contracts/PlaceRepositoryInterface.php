<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface PlaceRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get places by category slug.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getByCategory(string $categorySlug, int $perPage = 10);

    /**
     * Search places by name.
     */
    public function searchByName(string $query, int $limit = 5): Collection;

    /**
     * Get popular places based on rating or random if undefined.
     */
    public function getPopular(int $limit = 6): Collection;
}
