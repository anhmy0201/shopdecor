<?php

namespace App\Imports;

use App\Models\Sanpham;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SanphamImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $tenSanPham = trim($row[1] ?? '');
        $loaiId     = $row[0] ?? null;
        $gia        = $row[2] ?? 0;
        if (empty($tenSanPham) || empty($loaiId) || intval($gia) <= 0) {
            return null;
        }

        return new Sanpham([
            'loai_id'     => intval($loaiId),
            'ten_san_pham'=> $tenSanPham,
            'slug'        => $this->uniqueSlug($tenSanPham),
            'gia'         => intval($gia),
            'gia_cu'      => !empty($row[3]) ? intval($row[3]) : null,
            'mo_ta'       => $row[4] ?? null,
            'so_luong'    => intval($row[5] ?? 0),
            'co_bien_the' => 0,
        ]);
    }

    private function uniqueSlug(string $ten): string
    {
        $slug     = Str::slug($ten);
        $original = $slug;
        $i = 1;
        while (Sanpham::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }
}