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
        Schema::create('idea_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idea_id');
            $table->foreign('idea_id')->references('id')->on('ideas');
            $table->timestamp('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idea_logs');
    }
};
