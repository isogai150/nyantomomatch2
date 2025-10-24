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
            $table->unique(['userA_id', 'userB_id']);
            $table->foreignId('post_id')->constrained('posts')->comment('投稿ID');
            $table->string('transfer_status')->default('none')->comment('譲渡状態 none:未送付 / sent:資料送付済 / agreed_wait:相手合意待ち / agreed:両者合意済 / paid:決済完了');
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
