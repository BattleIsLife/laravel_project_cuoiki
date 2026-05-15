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
        Schema::create('fictions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('fiction_name', 100)->nullable(false);
            $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignUuid('series_id')->nullable(true)->references('id')->on('series')->onDelete('set null');
            $table->text('description')->nullable(true);
            $table->unsignedInteger('like_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fictions');
    }
};
