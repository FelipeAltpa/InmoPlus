<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('la documentación Swagger es pública', function () {
    $this->get('/docs/api')->assertOk();
    $this->get('/docs/api.json')->assertOk()->assertJsonPath('openapi', '3.1.0');
});

it('el sitio público enlaza a Swagger', function () {
    $this->get('/')->assertOk()->assertSee('/docs/api', false);
});
