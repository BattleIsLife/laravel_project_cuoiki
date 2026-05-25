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
        Schema::create('like_fiction_history', function (Blueprint $table) {
            $table->id(); // Keep a simple primary key
            $table->foreignUuid('fiction_id')->references('id')->on('fictions');
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->unique(['fiction_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('like_fiction_history');
    }
};
