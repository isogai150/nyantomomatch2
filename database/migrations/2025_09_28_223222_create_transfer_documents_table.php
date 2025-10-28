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
            $table->foreignId('pair_id')->comment('DMペアID')->constrained('pairs')->onDelete('cascade');
            $table->foreignId('transfer_id')->nullable()->comment('譲渡成立ID')->constrained('transfers')->nullOnDelete();
            $table->string('buyer_signature')->nullable()->comment('譲受者署名（乙サイン)');
            $table->date('signed_date')->nullable()->comment('署名日');
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
