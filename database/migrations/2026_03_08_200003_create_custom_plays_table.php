<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_plays', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label', 120);
            $table->string('category', 60)->nullable();
            $table->json('tags')->default('[]');
            $table->json('roster');
            $table->json('phases');
            $table->boolean('is_public')->default(false);
            $table->string('thumbnail', 512)->nullable();
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_plays');
    }
};
