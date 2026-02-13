<?php

namespace App\Policies;

use App\Models\Place;
use App\Models\User;

class PlacePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['view all destinations', 'view own destinations']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Place $place): bool
    {
        if ($user->can('view all destinations')) {
            return true;
        }

        if ($user->can('view own destinations')) {
            return $place->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create destinations');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Place $place): bool
    {
        if ($user->can('edit all destinations')) {
            return true;
        }

        if ($user->can('edit own destinations')) {
            return $place->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Place $place): bool
    {
        if ($user->can('delete all destinations')) {
            return true;
        }

        if ($user->can('delete own destinations')) {
            return $place->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Place $place): bool
    {
        return $user->can('delete all destinations');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Place $place): bool
    {
        return $user->can('delete all destinations');
    }
}
