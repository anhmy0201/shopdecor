<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sanpham_hinhanh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanpham_id')->constrained('sanpham')->onDelete('cascade');
            $table->string('duong_dan_anh');
            $table->boolean('la_anh_chinh')->default(false);
            $table->unsignedTinyInteger('thu_tu')->default(0);
            $table->timestamps();

            $table->index('sanpham_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanpham_hinhanh');
    }
};