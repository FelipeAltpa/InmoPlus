<?php

namespace App\Policies;

use App\Models\PropertyType;
use App\Models\User;

class PropertyTypePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, PropertyType $propertyType): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, PropertyType $propertyType): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, PropertyType $propertyType): bool
    {
        return $user->isAdmin();
    }

}
