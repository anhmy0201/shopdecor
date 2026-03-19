<?php

namespace App\Exports;

use App\Models\Magiamgia;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MagiamgiaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Magiamgia::all();
    }

    public function headings(): array
    {
        return [
            'Mã code',
            'Mô tả',
            'Kiểu giảm',
            'Giá trị',
            'Đơn hàng tối thiểu',
            'Giảm tối đa',
            'Số lượng',
            'Đã sử dụng',
            'Bắt đầu',
            'Kết thúc',
            'Kích hoạt',
        ];
    }

    public function map($row): array
    {
        return [
            $row->ma_code,
            $row->mo_ta,
            $row->kieu_giam === 'phan_tram' ? 'Phần trăm' : 'Cố định',
            $row->gia_tri,
            $row->don_hang_toi_thieu,
            $row->giam_toi_da,
            $row->so_luong,
            $row->da_su_dung,
            $row->bat_dau?->format('d/m/Y H:i'),
            $row->ket_thuc?->format('d/m/Y H:i'),
            $row->kich_hoat ? 'Có' : 'Không',
        ];
    }
}
