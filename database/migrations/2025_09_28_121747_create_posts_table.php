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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('投稿者ID');
            $table->string('title', 20)->comment('タイトル');
            $table->tinyInteger('gender')->default(0)->comment('性別 0：未入力 / 1：オス / 2：メス');
            $table->string('breed', 255)->comment('猫種');
            $table->tinyInteger('age')->comment('推定年齢');
            $table->unsignedInteger('cost')->comment('譲渡時負担費用');
            $table->string('region', 255)->comment('現在の住居地');
            $table->string('vaccination', 255)->comment('予防接種関係');
            $table->string('medical_history', 255)->comment('病歴');
            $table->text('description')->comment('詳細説明');
            $table->dateTime('start_date')->comment('掲載開始日');
            $table->dateTime('end_date')->nullable()->comment('掲載終了日');
            $table->tinyInteger('status')->default(0)->comment('ステータス 0：募集中 / 1：トライアル中 / 2：譲渡済み');
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
        Schema::dropIfExists('posts');
    }
};
