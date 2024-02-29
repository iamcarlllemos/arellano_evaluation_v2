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
        Schema::create('afears_questionaire_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionaire_id')
                ->constrained('afears_questionaire')
                ->onDelete('cascade');
            $table->foreignId('criteria_id')
                ->constrained('afears_criteria')
                ->onDelete('cascade');
            $table->string('item');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afears_questionaire_item');
    }
};
