<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_property', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->timestamps();

            // "property_id" needs its own index for the reverse query
            // ($property->clients). "client_id" is already covered as the
            // leading column of the unique index below.
            $table->index(['property_id']);

            // A client can only be interested in a given property once. Both
            // columns are NOT NULL, so a plain UNIQUE index (not a filtered
            // one) is enough: it rejects duplicate pairs and allows every
            // distinct combination.
            $table->unique(['client_id', 'property_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_property');
    }
};