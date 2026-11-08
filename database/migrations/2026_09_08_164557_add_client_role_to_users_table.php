<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /** Roles use a portable string column, so no schema change is needed. */
    public function up(): void
    {
        // Kept as a migration step for existing project history.
    }

    public function down(): void
    {
        // No schema change to reverse.
    }
};
