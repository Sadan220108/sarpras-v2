<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Penghitung nomor surat per tahun (dikunci saat membuat nomor)
        Schema::create('nomor_urut', function (Blueprint $table) {
            $table->unsignedSmallInteger('tahun')->primary();
            $table->unsignedInteger('terakhir')->default(0);
        });

        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('jabatan')->nullable();   // salinan saat pengajuan dibuat
            $table->string('unit')->nullable();
            $table->timestamps();
        });

        // Bagian A: sarana baru
        Schema::create('pengajuan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->string('nama_barang');
            $table->text('spesifikasi')->nullable();
            $table->string('link', 500)->nullable();   // link marketplace
            $table->unsignedBigInteger('biaya')->default(0);   // per satuan
            $table->unsignedInteger('jumlah')->default(1);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // Bagian B: perbaikan
        Schema::create('pengajuan_perbaikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->string('nama_sarana');
            $table->text('kerusakan');
            $table->string('lokasi')->nullable();
            $table->unsignedBigInteger('biaya')->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // Lampiran (spesifikasi, foto kerusakan, dll). Disimpan di disk privat.
        Schema::create('lampiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->string('nama_asli');
            $table->string('path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lampiran');
        Schema::dropIfExists('pengajuan_perbaikan');
        Schema::dropIfExists('pengajuan_items');
        Schema::dropIfExists('pengajuan');
        Schema::dropIfExists('nomor_urut');
    }
};
