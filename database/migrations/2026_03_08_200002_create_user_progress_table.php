<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('concept_id')->constrained()->cascadeOnDelete();
            $table->timestamp('completed_at')->useCurrent();

            $table->unique(['user_id', 'concept_id'], 'uq_progress');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
