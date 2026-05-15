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
        Schema::create('upvote_moderator_post_comment_history', function (Blueprint $table) {
            $table->foreignUuid('comment_id')->references('id')->on('moderator_post_comments');
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->primary(['comment_id', 'user_id']);
            $table->unsignedTinyInteger('count')->default(1); // 1 là upvote còn -1 là downvote, dùng để tính điểm tổng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upvote_moderator_post_comment_history');
    }
};
