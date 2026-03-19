<?php

namespace App\Imports;

use App\Models\Sanpham;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SanphamImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Sanpham([
            'loai_id'     => $row['ma_loai'],
            'ten_san_pham'=> $row['ten_san_pham'],
            'slug'        => Str::slug($row['ten_san_pham']),
            'gia'         => $row['gia'],
            'gia_cu'      => $row['gia_cu'] ?? null,
            'mo_ta'       => $row['mo_ta'] ?? null,
            'so_luong'    => $row['so_luong'] ?? 0,
            'co_bien_the' => 0,
        ]);
    }
}
