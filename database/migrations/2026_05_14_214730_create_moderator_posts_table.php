<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('moderator_posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 100)->unique()->nullable(false);
            $table->foreignUuid('moderator_id')->nullable(true)->references('id')->on('moderators')->onDelete('set null');
            $table->text('description')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderator_posts');
    }
};
