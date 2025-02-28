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
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->unsignedBigInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->enum('age_range', AgeRangeEnum::getValues())->default(null);
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
            $table->unsignedBigInteger('evaluation_id')->nullable();
            $table->foreign('evaluation_id')->references('id')->on('evaluations');
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
