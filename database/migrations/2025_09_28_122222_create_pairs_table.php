<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userA_id')->comment('AユーザーID')->constrained('users')->onDelete('cascade');
            $table->foreignId('userB_id')->comment('BユーザーID')->constrained('users')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts')->comment('投稿ID');

            $table->unique(['userA_id', 'userB_id', 'post_id'], 'pairs_users_post_unique');

            $table->timestamps();
            $table->softDeletes()->comment('削除日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pairs');
    }
};
