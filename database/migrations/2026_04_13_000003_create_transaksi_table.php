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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->nullable()
                ->constrained('orders')
                ->nullOnDelete();

            $table->foreignId('zakat_id')
                ->constrained('zakat')
                ->cascadeOnDelete();

            $table->foreignId('id_user')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->decimal('jumlah_bayar', 15, 2);
            $table->string('metode_pembayaran')->default('tunai');
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('nomor_transaksi')->nullable();
            $table->timestamp('tanggal_bayar')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
