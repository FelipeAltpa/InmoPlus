<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_type_id')->constrained('property_types')->onDelete('no action');
            $table->foreignId('user_id')->constrained('users')->onDelete('no action');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('neighborhood')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('area', 10, 2)->nullable();
            $table->unsignedSmallInteger('bedrooms')->default(0);
            $table->unsignedSmallInteger('bathrooms')->default(0);
            $table->enum('operation_type', ['venta', 'alquiler'])->default('venta');
            $table->enum('status', ['disponible', 'reservado', 'vendido', 'alquilado'])->default('disponible');
            $table->string('main_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};