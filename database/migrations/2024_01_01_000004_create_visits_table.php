<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visitor_id')->constrained('visitors')->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();

            // Siapa yang meng-approve/reject (selalu role admin)
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();

            // Siapa yang menginput data kunjungan ini: null jika self-service oleh
            // tamu sendiri, atau diisi id satpam jika tamu kategori prioritas.
            $table->foreignId('input_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('purpose')->nullable();

            $table->enum('status', [
                'pending', 'approved', 'rejected', 'checked_in', 'checked_out',
            ])->default('pending');

            $table->text('rejected_reason')->nullable();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();

            $table->timestamps();

            // Satu visitor tidak boleh punya lebih dari satu kunjungan yang masih
            // berjalan (belum checked_out) dalam waktu bersamaan — dijaga di level
            // aplikasi (lihat scope activeVisit di model Visitor), bukan constraint DB
            // karena "aktif" mencakup beberapa status (pending/approved/checked_in).
            $table->index(['visitor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
