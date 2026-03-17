<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tintuc_hinhanh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tintuc_id')
                  ->constrained('tintuc')
                  ->onDelete('cascade');        // xoá bài → xoá ảnh
            $table->string('duong_dan_anh');
            $table->string('chu_thich')->nullable(); // mô tả ảnh (optional)
            $table->unsignedTinyInteger('thu_tu')->default(0);
            $table->timestamps();
 
            $table->index('tintuc_id');
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('tintuc_hinhanh');
    }
};