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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userA_id')->comment('AユーザーID')->constrained('users')->onDelete('cascade');
            $table->foreignId('userB_id')->comment('BユーザーID')->constrained('users')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts')->comment('譲渡成立投稿ID');
            $table->timestamp('confirmed_at')->comment('合意日時');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
};
