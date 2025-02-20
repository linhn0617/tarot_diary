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
        Schema::table('diaries', function (Blueprint $table) {
            $table->dropForeign(['tarot_id']);
            $table->dropColumn('tarot_id');

            // 新增 tarot_specification_id 外鍵欄位
            $table->foreignId('tarot_specification_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diaries', function (Blueprint $table) {
            // 移除舊的外鍵和欄位
            $table->dropForeign(['tarot_specification_id']); // 修正這裡的欄位名稱
            $table->dropColumn('tarot_specification_id');

            // 新增 tarot_id 外鍵欄位，並設置外鍵約束
            $table->foreignId('tarot_id')->constrained('tarots')->cascadeOnDelete();
        });
    }
};
