<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chitiet_giohang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('giohang_id')->constrained('giohang')->onDelete('cascade');
            $table->foreignId('sanpham_id')->constrained('sanpham')->onDelete('cascade');
            $table->foreignId('bienthe_id')->nullable()->constrained('sanpham_bienthe')->nullOnDelete();
            $table->unsignedInteger('so_luong');
            $table->decimal('gia', 15, 2);
            $table->timestamps();

            $table->index(['giohang_id', 'bienthe_id']);
            $table->index(['giohang_id', 'sanpham_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chitiet_giohang');
    }
};