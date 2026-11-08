<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_visits', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('agent_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('requested_at');
            $table->dateTime('scheduled_at')->nullable();
            $table->string('status', 20)->default('pending')->index();
            $table->text('client_message')->nullable();
            $table->text('agent_note')->nullable();
            $table->timestamps();
            $table->index(['client_user_id', 'status']);
            $table->index(['agent_user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_visits');
    }
};
