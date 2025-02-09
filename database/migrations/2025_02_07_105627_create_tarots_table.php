<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TarotElementType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tarots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_upright');
            $table->enum('element', array_column(TarotElementType::cases(), 'value'));
            $table->text('file_path');
            $table->string('message1');
            $table->string('message2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarots');
    }
};
