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
        Schema::create('topic_categories', function (Blueprint $table) {
            $table->foreignId('topic_id')->nullable()->constrained();
            $table->foreignId('category_id')->nullable()->constrained();
            $table->primary(['topic_id', 'category_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_categories');
    }
};
