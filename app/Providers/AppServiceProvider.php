<?php

namespace App\Providers;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use App\Policies\PropertyPolicy;
use App\Policies\PropertyTypePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(Property::class, PropertyPolicy::class);
        Gate::policy(PropertyType::class, PropertyTypePolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        // La documentación de la API es pública también fuera de local.
        Gate::define('viewApiDocs', fn (?User $user = null) => true);
    }
}
