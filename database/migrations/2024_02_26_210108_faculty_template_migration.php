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
        Schema::create('afears_faculty_template', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')
                ->constrained('afears_faculty')
                ->onDelete('cascade');
            $table->foreignId('template_id')
                ->constrained('afears_curriculum_template')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afears_faculty_template');
    }
};
