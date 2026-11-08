<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('el seeder crea las cuentas iniciales con la clave por defecto', function () {
    $this->seed(DatabaseSeeder::class);

    $admin = User::where('email', 'admin@inmoplus.com')->first();

    expect($admin->role)->toBe('admin')
        ->and(Hash::check('password123', $admin->password))->toBeTrue()
        ->and(User::where('role', 'client')->count())->toBe(12);
});
