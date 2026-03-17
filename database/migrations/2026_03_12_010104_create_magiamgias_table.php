<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magiamgia', function (Blueprint $table) {
            $table->id();
            $table->string('ma_code', 50)->unique();
            $table->text('mo_ta')->nullable();
            $table->enum('kieu_giam', ['phan_tram', 'co_dinh']);
            $table->decimal('gia_tri', 15, 2);
            $table->decimal('don_hang_toi_thieu', 15, 2)->default(0);
            $table->decimal('giam_toi_da', 15, 2)->nullable();
            $table->unsignedInteger('so_luong')->nullable();
            $table->unsignedInteger('da_su_dung')->default(0);
            $table->timestamp('bat_dau')->nullable();
            $table->timestamp('ket_thuc')->nullable();
            $table->boolean('kich_hoat')->default(true);
            $table->timestamps();

            $table->index('kich_hoat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magiamgia');
    }
};