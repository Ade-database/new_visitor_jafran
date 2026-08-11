<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visit_feedback', function (Blueprint $table) {
            $table->id();

            // One-to-one optional terhadap visits: satu kunjungan maksimal satu feedback
            $table->foreignId('visit_id')->unique()->constrained('visits')->cascadeOnDelete();

            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_feedback');
    }
};
