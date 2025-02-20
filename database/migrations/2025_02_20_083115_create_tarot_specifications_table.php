<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tarot_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarot_id')->constrained()->cascadeOnDelete(); // 簡化的外鍵設定
            $table->boolean('is_upright');
            $table->string('message1')->nullable();
            $table->string('message2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarot_specifications');
    }
};
