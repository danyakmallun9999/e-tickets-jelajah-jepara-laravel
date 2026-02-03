<?php

namespace App\Services;

class StaticDataService
{
    /**
     * Get all culture items.
     *
     * @return \Illuminate\Support\Collection
     */
    /**
     * Get all culture items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCultures()
    {
        return collect(json_decode(json_encode(__('static_data.cultures'))));
    }

    /**
     * Get all culinary items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCulinaries()
    {
        return collect(json_decode(json_encode(__('static_data.culinaries'))));
    }
}
