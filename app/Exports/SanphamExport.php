<?php

namespace App\Exports;

use App\Models\Sanpham;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SanphamExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Sanpham::with('loai')->get();
    }

    public function headings(): array
    {
        return [
            'Mã loại',
            'Tên sản phẩm',
            'Giá',
            'Giá cũ',
            'Mô tả',
            'Số lượng',
            'Có biến thể',
            'Lượt xem',
            'Lượt mua',
        ];
    }

    public function map($row): array
    {
        return [
            $row->loai_id,
            $row->ten_san_pham,
            $row->gia,
            $row->gia_cu,
            $row->mo_ta,
            $row->so_luong,
            $row->co_bien_the ? 'Có' : 'Không',
            $row->luot_xem,
            $row->luot_mua,
        ];
    }
}
