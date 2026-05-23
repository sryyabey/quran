<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_share_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('token', 80)->unique();
            $table->string('title', 160)->nullable();
            $table->string('visibility', 16)->default('public'); // public|private
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->unsignedBigInteger('access_count')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->json('payload');
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['visibility', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_share_links');
    }
};
