<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dia_chi_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ho_ten');
            $table->string('so_dien_thoai', 15);
            $table->string('dia_chi_chi_tiet');
            $table->string('phuong_xa');
            $table->string('quan_huyen');
            $table->string('tinh_thanh');
            $table->boolean('mac_dinh')->default(false);
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dia_chi_user');
    }
};