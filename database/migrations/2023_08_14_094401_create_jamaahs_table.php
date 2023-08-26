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
        Schema::create('jamaahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('agents')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained('schedules')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('nik');
            $table->string('fullname');
            $table->string('pob');
            $table->date('dob');
            $table->enum('gender', ['L', 'P']);
            $table->string('phone');
            $table->enum('martial_status', ['Belum Menikah', 'Sudah Menikah']);
            $table->string('profession');
            $table->text('address');
            $table->string('heir_name')->nullable();
            $table->string('heir_relation')->nullable();
            $table->string('heir_phone')->nullable();
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
        Schema::dropIfExists('jamaahs');
    }
};