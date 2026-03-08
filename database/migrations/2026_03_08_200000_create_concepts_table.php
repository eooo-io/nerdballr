<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('concepts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug', 120)->unique();
            $table->string('label', 120);
            $table->string('category', 60)->index();
            $table->string('subcategory', 60)->nullable();
            $table->json('tags')->default('[]');
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->index();
            $table->json('layers')->default('[]');
            $table->text('description');
            $table->longText('explanation');
            $table->json('roster');
            $table->json('phases');
            $table->json('counters')->default('[]');
            $table->json('related')->default('[]');
            $table->text('ai_context');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('concepts');
    }
};
