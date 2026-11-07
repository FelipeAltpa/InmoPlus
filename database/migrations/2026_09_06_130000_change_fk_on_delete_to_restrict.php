<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Drop existing foreign keys
            $table->dropForeign(['user_id']);
            $table->dropForeign(['property_type_id']);

            // Recreate with ON DELETE RESTRICT
            $table->foreign('user_id', 'properties_user_id_foreign')
                ->references('id')
                ->on('users')
                ->onDelete('no action');

            $table->foreign('property_type_id', 'properties_property_type_id_foreign')
                ->references('id')
                ->on('property_types')
                ->onDelete('no action');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Drop RESTRICT foreign keys
            $table->dropForeign(['user_id']);
            $table->dropForeign(['property_type_id']);

            // Recreate with ON DELETE CASCADE (original)
            $table->foreign('user_id', 'properties_user_id_foreign')
                ->references('id')
                ->on('users')
                ->onDelete('no action');

            $table->foreign('property_type_id', 'properties_property_type_id_foreign')
                ->references('id')
                ->on('property_types')
                ->onDelete('no action');
        });
    }
};
