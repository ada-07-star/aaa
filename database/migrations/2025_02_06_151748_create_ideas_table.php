<?php

use App\Enums\CurrentStateEnum;
use App\Enums\ParticipationTypeEnum;
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
            $table->unsignedBigInteger('topic_id');
            $table->foreign('topic_id')->references('id')->on('topics');
            $table->string('title', 500);
            $table->text('description');
            $table->Boolean('is_published');
            $table->enum('current_state', CurrentStateEnum::values())->default(CurrentStateEnum::DRAFT->value);
            $table->enum('participation_type', ParticipationTypeEnum::values())->default(ParticipationTypeEnum::INDIVIDUAL->value);
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
