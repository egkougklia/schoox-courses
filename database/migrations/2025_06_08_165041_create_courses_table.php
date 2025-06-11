<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\CourseStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('title', length: 255);
            $table->text('description');
            $table->enum('status', array_column(CourseStatus::cases(), 'value'));
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('name');
        });

        Schema::create('course_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('course_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('tag_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('courses');
    }
};
