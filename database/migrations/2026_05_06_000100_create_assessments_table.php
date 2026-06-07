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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Input form assessment
            $table->decimal('gaji', 15, 2)->default(0);
            $table->decimal('tabungan', 15, 2)->default(0);
            $table->decimal('emas_gram', 10, 2)->default(0);
            $table->decimal('hutang', 15, 2)->default(0);

            // Parameter perhitungan (bisa diisi dari harga pasar saat assessment)
            $table->decimal('harga_emas_per_gram', 15, 2)->default(0);
            $table->decimal('harga_beras_per_kg', 15, 2)->default(0);
            $table->unsignedInteger('jumlah_jiwa_fitrah')->default(1);

            // Hasil kalkulasi tersimpan (snapshot)
            $table->decimal('nilai_emas_rupiah', 15, 2)->default(0);
            $table->decimal('total_harta_bersih', 15, 2)->default(0);
            $table->decimal('nisab_mal_rupiah', 15, 2)->default(0);
            $table->boolean('wajib_zakat_mal')->default(false);
            $table->decimal('nominal_zakat_mal', 15, 2)->default(0);
            $table->decimal('nominal_zakat_fitrah', 15, 2)->default(0);

            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
