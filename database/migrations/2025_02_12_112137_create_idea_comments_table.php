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
            $table->unsignedBigInteger('idea_id');
            $table->foreign('idea_id')->references('id')->on('ideas');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('idea_comments');
            $table->integer('likes')->nullable();
            $table->enum('status', ['active', 'DRAFT'])->default('active');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
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
