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
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained();
            $table->string('title', 500);
            $table->text('description');
            $table->Boolean('is_published');
            $table->enum('current_state', ['draft', 'active', 'archived'])->default('draft');
            $table->enum('participation_type', ['team', 'individual'])->default('individual');
            $table->integer('final_score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ideas');
    }
};
