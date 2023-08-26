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
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->nullable()->constrained('master_surats','id')->cascadeOnUpdate()->nullOnDelete();
            $table->string('tujuan');
            $table->string('no_surat');
            $table->string('alamat');
            $table->date('tanggal');
            $table->string('keterangan');
            $table->string('perihal');
            $table->string('file');
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};