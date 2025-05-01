<?php

use App\Enums\AgeRangeEnum;
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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->foreignId('department_id')->constrained();
            $table->foreignId('language_id')->constrained();
            $table->string('age_range');
            $table->bigInteger('gender')->nullable();
            $table->string('thumb_image', 500)->nullable();
            $table->string('cover_image', 500)->nullable();
            $table->Timestamp('submit_date_from');
            $table->Timestamp('submit_date_to')->nullable();
            $table->Timestamp('consideration_date_from')->nullable();
            $table->Timestamp('consideration_date_to')->nullable();
            $table->Timestamp('plan_date_from')->nullable();
            $table->Timestamp('plan_date_to')->nullable();
            $table->string('current_state', 50);
            $table->integer('judge_number');
            $table->integer('minimum_score');
            $table->foreignId('evaluation_id')->nullable()->constrained();
            $table->Boolean('status');
            $table->Boolean('is_archive');
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
