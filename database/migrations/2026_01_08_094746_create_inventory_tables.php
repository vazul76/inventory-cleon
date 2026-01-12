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
        // Categories Table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Stock Snapshots Table
        Schema::create('stock_snapshots', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique();
            $table->integer('total_alat_available')->default(0);
            $table->integer('total_material_stock')->default(0);
            $table->timestamps();
        });

        // Alats Table
        Schema::create('alats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->integer('available')->default(1);
            $table->enum('status', ['available', 'borrowed', 'maintenance'])->default('available');
            $table->timestamps();
        });

        // Materials Table
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        // Peminjaman Alats Table
        Schema::create('peminjaman_alats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alat_id')->constrained('alats')->cascadeOnDelete();
            $table->string('nama_peminjam');
            $table->integer('jumlah');
            $table->dateTime('tanggal_pinjam');
            $table->dateTime('tanggal_kembali')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Pengambilan Materials Table
        Schema::create('pengambilan_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->string('nama_pengambil');
            $table->integer('jumlah');
            $table->dateTime('tanggal_ambil');
            $table->dateTime('tanggal_kembali')->nullable();
            $table->enum('status', ['diambil', 'dikembalikan', 'dipakai'])->default('diambil');
            $table->text('keperluan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengambilan_materials');
        Schema::dropIfExists('peminjaman_alats');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('alats');
        Schema::dropIfExists('stock_snapshots');
        Schema::dropIfExists('categories');
    }
};
