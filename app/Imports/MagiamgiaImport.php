<?php

namespace App\Imports;

use App\Models\Magiamgia;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MagiamgiaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Magiamgia([
            'ma_code'             => strtoupper($row['ma_code']),
            'mo_ta'               => $row['mo_ta'] ?? null,
            'kieu_giam'           => $row['kieu_giam'],        // phan_tram hoặc co_dinh
            'gia_tri'             => $row['gia_tri'],
            'don_hang_toi_thieu'  => $row['don_hang_toi_thieu'] ?? 0,
            'giam_toi_da'         => $row['giam_toi_da'] ?? null,
            'so_luong'            => $row['so_luong'] ?? null,
            'bat_dau'             => $row['bat_dau'] ?? null,
            'ket_thuc'            => $row['ket_thuc'] ?? null,
            'kich_hoat'           => $row['kich_hoat'] ?? 1,
        ]);
    }
}
