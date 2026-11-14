<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'users_role_idx');             // role=admin/editor filters and counts
            $table->index('status', 'users_status_idx');         // status=blocked filter and count
            $table->index('created_at', 'users_created_at_idx'); // latest() and "this week" count
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->index('created_at', 'posts_created_at_idx');                      // admin list latest(), month stats
            $table->index(['published', 'created_at'], 'posts_published_created_idx'); // home page list, status filter
            $table->index(['published', 'views_counter'], 'posts_published_views_idx'); // popular posts
            $table->index(['author_id', 'created_at'], 'posts_author_created_idx');   // author's own posts
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index(['post_id', 'status', 'created_at'], 'comments_post_status_created_idx'); // comments under a post
            $table->index(['status', 'created_at'], 'comments_status_created_idx');                 // admin filter + counts
            $table->index(['user_id', 'created_at'], 'comments_user_created_idx');                  // "my comments"
        });

        Schema::table('post_views', function (Blueprint $table) {
            $table->index('created_at', 'post_views_created_at_idx');   // "views today" count
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->index('created_at', 'messages_created_at_idx');     // inbox latest()
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_idx');
            $table->dropIndex('users_status_idx');
            $table->dropIndex('users_created_at_idx');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_created_at_idx');
            $table->dropIndex('posts_published_created_idx');
            $table->dropIndex('posts_published_views_idx');
            $table->dropIndex('posts_author_created_idx');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('comments_post_status_created_idx');
            $table->dropIndex('comments_status_created_idx');
            $table->dropIndex('comments_user_created_idx');
        });

        Schema::table('post_views', function (Blueprint $table) {
            $table->dropIndex('post_views_created_at_idx');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('messages_created_at_idx');
        });
    }
};