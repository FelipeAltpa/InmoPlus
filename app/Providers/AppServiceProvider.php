<?php

namespace App\Providers;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use App\Policies\PropertyPolicy;
use App\Policies\PropertyTypePolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
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

        VerifyEmail::toMailUsing(fn (object $notifiable, string $url) => (new MailMessage)
            ->subject('Verifica tu correo en InmoPlus')
            ->greeting('¡Hola, '.$notifiable->name.'!')
            ->line('Gracias por registrarte. Haz clic en el botón para confirmar tu correo y activar tu cuenta.')
            ->action('Verificar correo', $url)
            ->line('El enlace vence en 60 minutos. Si no creaste esta cuenta, ignora este mensaje.')
            ->salutation('Saludos, el equipo de InmoPlus'));
    }
}
