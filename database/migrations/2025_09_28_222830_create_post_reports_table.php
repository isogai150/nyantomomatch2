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
        Schema::create('post_reports', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')
        ->constrained('users')
        ->comment('通報者ID');
    $table->foreignId('post_id')
        ->constrained('posts')
        ->onDelete('cascade')
        ->comment('通報対象の投稿ID');
    $table->tinyInteger('status')
        ->default(0)
        ->comment('通報ステータス 0：未対応 / 1：対応済み / 2：却下');
    $table->timestamps();
    $table->softDeletes()->comment('削除日');

    $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_reports');
    }
};
