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
        Schema::create('chapters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('chapter_name', 100)->unique()->nullable(false);
            $table->smallInteger('chapter_order')->default(0);
            $table->foreignUuid('fiction_id')->references('id')->on('fictions')->onDelete('cascade');
            $table->mediumText('content')->nullable(true);
            $table->smallInteger('is_posted')->default(0);
            $table->unsignedInteger('watch_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
