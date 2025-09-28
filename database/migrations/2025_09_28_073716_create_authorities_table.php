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
        Schema::create('authorities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('権限付与対象');
            $table->text('reason')->comment('申請理由');
            $table->tinyInteger('status')->default(0)->comment('ステータス 0：申請中 / 1：承認 / 2：却下');
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
        Schema::dropIfExists('authorities');
    }
};
