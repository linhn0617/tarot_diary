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
        Schema::table('tarots', function (Blueprint $table) {
            $table->dropColumn(['is_upright', 'message1', 'message2']); // 刪除多個欄位
            $table->renameColumn('file_path', 'image_path');            // 修改欄位名稱
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarots', function (Blueprint $table) {
            $table->boolean('is_upright')->default(true);               // 新增欄位
            $table->string('message1')->nullable();                        // 新增欄位
            $table->string('message2')->nullable();                        // 新增欄位
            $table->renameColumn('image_path', 'file_path');            // 還原欄位名稱
        });
    }
};
