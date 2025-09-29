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
        Schema::create('transfer_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_id')->comment('譲渡成立ID')->constrained('transfers');
            $table->text('conditions')->comment('譲渡条件資料');
            $table->text('contract')->comment('譲渡契約書');
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
        Schema::dropIfExists('transfer_documents');
    }
};
