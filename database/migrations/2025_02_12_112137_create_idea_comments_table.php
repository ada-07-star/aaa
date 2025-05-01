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
        Schema::create('idea_comments', function (Blueprint $table) {
            $table->id();
            $table->text('comment_text');
            $table->foreignId('idea_id')->constrained();
            $table->foreignId('parent_id')->nullable()->constrained('idea_comments');
            $table->integer('likes')->nullable();
            $table->enum('status', ['published', 'draft'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idea_comments');
    }
};
