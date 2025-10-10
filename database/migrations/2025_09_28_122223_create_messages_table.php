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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('メッセージ送信者ID');
            $table->foreignId('pair_id')->constrained('pairs')->onDelete('cascade')->comment('DMのID');
            $table->text('content')->comment('メッセージテキスト');
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
        Schema::dropIfExists('messages');
    }
};
