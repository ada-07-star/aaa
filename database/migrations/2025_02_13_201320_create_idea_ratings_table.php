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
        Schema::create('idea_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idea_id')->nullable();
            $table->foreign('idea_id')->references('id')->on('ideas');
            $table->unsignedBigInteger('rate_number');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idea_ratings');
    }
};
