<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // The customer whose conversation this message belongs to.
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Who actually wrote it: the customer, or an admin replying.
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();

            // Stored so unread counts never need to join users.
            $table->boolean('is_from_admin')->default(false);

            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Unread badge queries: unread customer messages / unread admin replies.
            $table->index(['is_from_admin', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
