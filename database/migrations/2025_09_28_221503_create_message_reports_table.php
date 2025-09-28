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
        Schema::create('message_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->comment('通報者ID');
            $table->foreignId('reported_user_id')->constrained('users')->comment('通報対象者ID');
            $table->foreignId('pair_id')->constrained('pairs')->comment('通報対象のDMID');
            $table->foreignId('message_id')->constrained('messages')->comment('通報対象のメッセージID');
            $table->string('content')->comment('通報内容');
            $table->tinyInteger('status')->default(0)->comment('通報ステータス 0：未対応 / 1：対応済み / 2：却下');
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
        Schema::dropIfExists('message_reports');
    }
};
