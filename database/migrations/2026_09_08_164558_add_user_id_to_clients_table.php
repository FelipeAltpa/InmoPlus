<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Link each client to its authentication record (User -> Client, 1:1).
     *
     * "users" holds authentication data and "clients" holds CRM data. The
     * unique user_id makes the relationship one-to-one, and it is nullable so
     * a client record may exist before the corresponding login account.
     *
     * On SQL Server a plain UNIQUE index only allows a single NULL, so a
     * filtered unique index is used to keep the 1:1 while allowing multiple
     * clients without a linked user account.
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('no action');
        });

        if (DB::getDriverName() === 'sqlsrv') {
            DB::statement(
                'CREATE UNIQUE INDEX clients_user_id_unique ON clients (user_id) WHERE user_id IS NOT NULL'
            );
        } else {
            Schema::table('clients', function (Blueprint $table) {
                $table->unique('user_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
            $table->dropConstrainedForeignId('user_id');
        });
    }
};