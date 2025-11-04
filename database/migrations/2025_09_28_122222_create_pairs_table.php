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
            $table->string('transfer_status')->default('none')->comment('譲渡状態 none:未送付 / sent:資料送付済 / submitted:書類提出済（相手合意待ち） / agreed_wait:片方同意済 / agreed:両者合意済 / paid:決済完了');
            $table->unsignedBigInteger('agreed_user_id')->nullable()->comment('合意したユーザーID');
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
