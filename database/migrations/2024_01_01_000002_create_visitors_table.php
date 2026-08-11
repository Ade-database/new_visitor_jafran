<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Nullable karena tamu prioritas yang diinput satpam bisa saja tidak
            // mencantumkan nomor HP. Unique hanya berlaku untuk nilai yang terisi,
            // dipakai sebagai kunci lookup tamu baru vs tamu lama.
            $table->string('phone_number')->nullable()->unique();

            $table->string('company')->nullable();

            // Kategori tamu: reguler (self-service) atau prioritas (input satpam)
            $table->enum('category', ['reguler', 'prioritas'])->default('reguler');

            // Foto wajah, wajib diisi untuk kedua kategori (divalidasi di form/request)
            $table->string('photo_path');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
